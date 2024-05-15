<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use xGrz\Dhl24\Enums\DomesticShipmentType;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Helpers\Money;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;
use xGrz\Dhl24\Livewire\Forms\ShipmentContactForm;
use xGrz\Dhl24\Livewire\Forms\ShipmentRecipientForm;
use xGrz\Dhl24\Livewire\Forms\ShippingServices;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class CreateShipment extends Component
{
    public ShipmentRecipientForm $recipient;
    public ShipmentContactForm $contact;

    public ShippingServices $services;


    #[Validate]
    public array $items = [];

    public function mount(): void
    {
        self::addItem();
        $this->services->getContentSuggestions();
        $this->services->getCostCenters();
    }

    public function rules(): array
    {
        return (new StoreShipmentRequest())->rules();
    }

    public function render(): View
    {
        return view('dhl::shipments.livewire.create-shipment', [
            'shipmentTypes' => ShipmentItemType::cases(),
            'deliveryTypes' =>  DomesticShipmentType::getOptions(),
        ]);
    }

    public function createPackage(): void
    {
        $this->validate();
        $wizard = new ShipmentWizard(DomesticShipmentType::DOMESTIC);
        $wizard->shipper()
            ->setName('BRAMSTAL')
            ->setStreet('Sęczkowa')
            ->setHouseNumber('96')
            ->setPostalCode('03986')
            ->setCity('Warszawa')
            ->setContactEmail('biuro@bramstal.pl')
            ->setContactPhone('501335555');
        $wizard->receiver()
            ->setName($this->recipient->name)
            ->setStreet($this->recipient->street)
            ->setHouseNumber($this->recipient->houseNumber)
            ->setPostalCode($this->recipient->postalCode)
            ->setCity($this->recipient->city)
            ->setContactEmail($this->contact->email ?? null)
            ->setContactPhone($this->contact->phone ?? null);

        foreach ($this->items as $item) {
            $wizard
                ->addItem(ShipmentItemType::findByName(
                    $item['type']),
                    $item['quantity'],
                    $item['width'],
                    $item['height'],
                    $item['length'],
                    $item['weight']
                );

        }
        $wizard->services()
            ->setShipmentType(DomesticShipmentType::tryFrom($this->services->deliveryService))
            ->setCollectOnDelivery( $this->services->getCod())
            ->setSelfCollect($this->services->owl)
            ->setInsurance( $this->services->getValue())
            ->setPreDeliveryInformation($this->services->pdi)
            ->setReturnOnDelivery($this->services->rod);

        $wizard->setContent($this->services->content);
        $wizard->setShipmentDate(now()->addDays(3));

        try {
            DHL24::createShipment($wizard);
            $wizard->getModel()->fill(['shipment_id' => 2103821])->save();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

    }

    public function addItem(): void
    {
        $this->items[] = self::getItemDefinition(ShipmentItemType::PACKAGE);
    }

    public function updatedRecipientPostalCode(): void
    {
        strlen($this->recipient->postalCode) > 4
            ? self::getServicesForPostalCode()
            : $this->services->services = [];
    }

    public function changeShipmentType(ShipmentItemType $type): array
    {
        return self::getItemDefinition($type);
    }

    public function updatedItems(mixed $value, string $arrayKey): void
    {
        [$key, $prop] = explode('.', $arrayKey);
        if ($prop === 'type') {
            $this->items[$key] = self::changeShipmentType(ShipmentItemType::findByName($value));
        }
        self::shouldBeNonStandard($this->items[$key]);
        $this->validate();
    }

    public function removePackage(int $index): void
    {
        if (count($this->items) < 2) return;
        unset($this->items[$index]);
    }

    private function shouldBeNonStandard(array &$item): void
    {
        $shouldBeNonStandard = false;
        if (isset($item['width']) && $item['width'] > 120) $shouldBeNonStandard = true;
        if (isset($item['height']) && $item['height'] > 120) $shouldBeNonStandard = true;
        if (isset($item['length']) && $item['length'] > 120) $shouldBeNonStandard = true;
        $item['shouldBeNonStandard'] = $shouldBeNonStandard;
    }

    private function getItemDefinition(ShipmentItemType $type): array
    {
        $item['type'] = $type->name;
        $item['quantity'] = 1;
        $attributes = $type->getAttributes();
        if (in_array('weight', $attributes)) $item['weight'] = $type->getDefaultWeight();
        if (in_array('width', $attributes)) $item['width'] = $type->getDefaultWidth();
        if (in_array('height', $attributes)) $item['height'] = $type->getDefaultHeight();
        if (in_array('length', $attributes)) $item['length'] = $type->getDefaultLength();
        if (in_array('nonStandard', $attributes)) {
            $item['nonStandard'] = false;
            $item['shouldBeNonStandard'] = false;
        }
        return $item;
    }

    public function updatedServicesCod($codAmount)
    {
        $shipmentValue = Money::isValid($this->services->value) ? Money::from($this->services->value)->toNumber() : 0;
        $codValue = Money::isValid($codAmount) ? Money::from($codAmount)->toNumber() : 0;
        if (($codValue > $shipmentValue) && $codValue) {
            $this->services->value = Money::from($codValue)->format(',', ' ');
        }
    }


}
