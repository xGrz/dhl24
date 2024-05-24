<?php

namespace xGrz\Dhl24\Wizard;

use Illuminate\Support\Collection;
use xGrz\Dhl24\Actions\CreateShipment;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLItem;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentWizard
{
    private DHLShipment $shipment;

    public function __construct(?DHLShipment $shipment = null)
    {
        $this->shipment = $shipment ?? new DHLShipment();
    }

    public function setShipperName(string $name): static
    {
        $this->shipment->shipper_name = $name;
        return $this;
    }

    public function setShipperPostalCode(string $postalCode): static
    {
        $this->shipment->shipper_postal_code = $postalCode;
        return $this;
    }

    public function setShipperCity(string $city): static
    {
        $this->shipment->shipper_city = $city;
        return $this;
    }

    public function setShipperStreet(string $street): static
    {
        $this->shipment->shipper_street = $street;
        return $this;
    }

    public function setShipperHouseNumber(string $houseNumber): static
    {
        $this->shipment->shipper_house_number = $houseNumber;
        return $this;
    }

    public function setShipperContactPerson(string $contactPerson): static
    {
        $this->shipment->shipper_contact_person = $contactPerson;
        return $this;
    }

    public function setShipperContactPhone(string $contactPhone): static
    {
        $this->shipment->shipper_contact_phone = $contactPhone;
        return $this;
    }

    public function setShipperContactEmail(string $contactEmail): static
    {
        $this->shipment->shipper_contact_email = $contactEmail;
        return $this;
    }

    public function setReceiverType(DHLAddressType $addressType): static
    {
        $this->shipment->receiver_type = $addressType;
        return $this;
    }

    public function setReceiverName(string $name): static
    {
        $this->shipment->receiver_name = $name;
        return $this;
    }

    public function setReceiverPostalCode(string $postalCode, string $country = 'PL'): static
    {
        $this->shipment->receiver_country = $country;
        $this->shipment->receiver_postal_code = $postalCode;
        return $this;
    }

    public function setReceiverCity(string $city): static
    {
        $this->shipment->receiver_city = $city;
        return $this;
    }

    public function setReceiverStreet(string $street): static
    {
        $this->shipment->receiver_street = $street;
        return $this;
    }

    public function setReceiverHouseNumber(string $houseNumber): static
    {
        $this->shipment->receiver_house_number = $houseNumber;
        return $this;
    }

    public function setReceiverContactPerson(string $contactPerson): static
    {
        $this->shipment->receiver_contact_person = $contactPerson;
        return $this;
    }

    public function setReceiverContactPhone(string $contactPhone): static
    {
        $this->shipment->receiver_contact_phone = $contactPhone;
        return $this;
    }

    public function setReceiverContactEmail(string $contactEmail): static
    {
        $this->shipment->receiver_contact_email = $contactEmail;
        return $this;
    }

    public function setShipmentType(DHLDomesticShipmentType $shipmentType): static
    {
        $this->shipment->product = $shipmentType;
        return $this;
    }

    public function setCollectOnDelivery(int|float $amount, string $reference = null): static
    {
        $this->shipment->collect_on_delivery = $amount;
        if ($reference) $this->shipment->collect_on_delivery_reference = $reference;
        if (!$this->shipment->insurance || $this->shipment->insurance < $amount) {
            $this->shipment->insurance = $amount;
        }
        return $this;
    }

    public function setShipmentValue(int|float $amount): static
    {
        if ($this->shipment->collect_on_delivery && $this->shipment->collect_on_delivery > $amount) {
            $this->shipment->insurance = $this->shipment->collect_on_delivery;
        } else {
            $this->shipment->insurance = $amount;
        }
        return $this;
    }

    public function setContent(string $content): static
    {
        $this->shipment->content = $content;
        return $this;
    }

    public function setCostCenter(DHLCostCenter $costCenter): static
    {
        $this->shipment->cost_center()->associate($costCenter);
        return $this;
    }

    public function addItem(DHLShipmentItemType $type, int $quantity = 1, float|int $weight = null, int $width = null, int $height = null, int $length = null, bool $nonStandard = null): static
    {
        $item = new DHLItem([
            'type' => $type,
            'quantity' => $quantity
        ]);
        if ($weight) $item->weight = $weight;
        if ($width) $item->width = $width;
        if ($height) $item->height = $height;
        if ($length) $item->length = $length;
        if ($nonStandard) $item->non_standard = $nonStandard;

        $this->shipment->items->add($item);
        return $this;
    }

    public function create()
    {
        $shipmentNumber = (new CreateShipment())->create($this);
        $this->shipment->fill(['number' => $shipmentNumber])->save();
        return $shipmentNumber;
    }

    public function getPayload(): array
    {
        $payload = collect([
            'shipper' => $this->getShipperPayload(),
            'receiver' => $this->getReceiverPayload(),
            'pieceList' => $this->getPieceListPayload(),
            'service' => $this->getServicesPayload(),
            'payment' => $this->getPaymentPayload(),
        ])
            ->when($this->shipment->shipment_date,
                fn(Collection $payload) => $payload->put('shipmentDate', $this->shipment->shipment_date->format('Y-m-d')),
                fn(Collection $payload) => $payload->put('shipmentDate', now()->format('Y-m-d')),
            )
            ->when($this->shipment->comment, fn(Collection $payload) => $payload->put('comment', $this->shipment->comment))
            ->put('content', $this->shipment->content)
            ->put('skipRestrictionCheck', true);
        return $payload->toArray();
    }

    private function getServicesPayload(): ?array
    {
        $services = collect()
            ->put('product', $this->shipment->product?->value)
            ->when($this->shipment->collectOnDelivery, function (Collection $services) {
                return $services
                    ->put('collectOnDelivery', true)
                    ->put('collectOnDeliveryValue', $this->shipment->collect_on_delivery)
                    ->put('collectOnDeliveryForm', 'BANK_TRANSFER');
            })
            ->when($this->shipment->collect_on_delivery_reference, function (Collection $services) {
                return $services->put('collectOnDeliveryReference', $this->shipment->collect_on_delivery_reference);
            })
            ->when($this->shipment->insurance, function (Collection $services) {
                return $services->put('insurance', true)->put('insuranceValue', $this->shipment->insurance);
            });

        return $services->toArray();
    }

    public function getPaymentPayload(): array
    {
        $payment = collect([
            'paymentMethod' => 'BANK_TRANSFER',
            'payerType' => $this->payer_type ?? 'SHIPPER',
            'accountNumber' => DHLConfig::getSapNumber(),
        ])
            ->when($this->shipment->cost_center, fn(Collection $payment) => $payment->put('costsCenter', $this->shipment->cost_center->name));

        return $payment->toArray();
    }

    private function getShipperPayload(): array
    {
        $shipper = [
            'name' => $this->shipment->shipper_name,
            'postalCode' => $this->shipment->shipper_postal_code,
            'city' => $this->shipment->shipper_city,
            'street' => $this->shipment->shipper_street,
            'houseNumber' => $this->shipment->shipper_house_number,
        ];
        if ($this->shipment->shipper_contact_phone) $shipper['contactPhone'] = $this->shipment->shipper_contact_phone;
        if ($this->shipment->shipper_contact_email) $shipper['contactEmail'] = $this->shipment->shipper_contact_email;
        if ($this->shipment->shipper_contact_name) $shipper['contactName'] = $this->shipment->shipper_contact_name;
        return $shipper;
    }

    private function getReceiverPayload(): array
    {
        $receiver = [
            'addressType' => $this->shipment->receiver_type?->value,
            'country' => $this->shipment->receiver_country,
            'name' => $this->shipment->receiver_name,
            'postalCode' => $this->shipment->receiver_postal_code,
            'city' => $this->shipment->receiver_city,
            'street' => $this->shipment->receiver_street ?? $this->shipment->receiver_city,
            'houseNumber' => $this->shipment->receiver_house_number,
        ];
        if ($this->shipment->receiver_contact_phone) $receiver['contactPhone'] = $this->shipment->receiver_contact_phone;
        if ($this->shipment->receiver_contact_email) $receiver['contactEmail'] = $this->shipment->receiver_contact_email;
        if ($this->shipment->receiver_contact_name) $receiver['contactName'] = $this->shipment->receiver_contact_name;
        return $receiver;
    }

    private function getPieceListPayload(): array
    {
        $pieceList = $this->shipment->items->map(function (DHLItem $item) {
            $dhlItem = $item->only(['type', 'quantity', 'weight', 'length', 'width', 'height', 'non_standard']);
            foreach ($dhlItem as $prop => $value) {
                if ($value instanceof \BackedEnum) $dhlItem[$prop] = $value->value;
                if (empty($value)) unset($dhlItem[$prop]);
                if ($item->non_standard) $dhlItem['nonStandard'] = true;
            }
            return $dhlItem;
        });
        return $pieceList->toArray();
    }
}
