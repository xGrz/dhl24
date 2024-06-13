<?php

namespace xGrz\Dhl24\Wizard;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use xGrz\Dhl24\Actions\Cost;
use xGrz\Dhl24\Actions\CreateShipment;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Helpers\DHLIntelligentCostSaver;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLItem;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentWizard
{
    private DHLShipment $shipment;

    public function __construct(?DHLShipment $shipment = null)
    {
        if ($shipment) {
            $this->shipment = $shipment;
        } else {
            $this->shipment = new DHLShipment();
            self::shipmentDate();
        }
    }

    public function shipperName(string $name): static
    {
        $this->shipment->shipper_name = $name;
        return $this;
    }

    public function shipperPostalCode(string $postalCode): static
    {
        $this->shipment->shipper_postal_code = $postalCode;
        return $this;
    }

    public function shipperCity(string $city): static
    {
        $this->shipment->shipper_city = $city;
        return $this;
    }

    public function shipperStreet(string $street): static
    {
        $this->shipment->shipper_street = $street;
        return $this;
    }

    public function shipperHouseNumber(string $houseNumber): static
    {
        $this->shipment->shipper_house_number = $houseNumber;
        return $this;
    }

    public function shipperContactPerson(string $contactPerson = null): static
    {
        $this->shipment->shipper_contact_person = $contactPerson;
        return $this;
    }

    public function shipperContactPhone(string $contactPhone = null): static
    {
        $this->shipment->shipper_contact_phone = $contactPhone;
        return $this;
    }

    public function shipperContactEmail(string $contactEmail = null): static
    {
        $this->shipment->shipper_contact_email = $contactEmail;
        return $this;
    }

    public function receiverType(DHLAddressType $addressType): static
    {
        $this->shipment->receiver_type = $addressType;
        return $this;
    }

    public function receiverName(string $name): static
    {
        $this->shipment->receiver_name = $name;
        return $this;
    }

    public function receiverPostalCode(string $postalCode, string $country = 'PL'): static
    {
        $this->shipment->receiver_country = $country;
        $this->shipment->receiver_postal_code = $postalCode;
        return $this;
    }

    public function receiverCity(string $city): static
    {
        $this->shipment->receiver_city = $city;
        return $this;
    }

    public function receiverStreet(string $street): static
    {
        $this->shipment->receiver_street = $street;
        return $this;
    }

    public function receiverHouseNumber(string $houseNumber): static
    {
        $this->shipment->receiver_house_number = $houseNumber;
        return $this;
    }

    public function receiverContactPerson(string $contactPerson = null): static
    {
        $this->shipment->receiver_contact_person = $contactPerson;
        return $this;
    }

    public function receiverContactPhone(string $contactPhone = null): static
    {
        $this->shipment->receiver_contact_phone = $contactPhone;
        return $this;
    }

    public function receiverContactEmail(string $contactEmail = null): static
    {
        $this->shipment->receiver_contact_email = $contactEmail;
        return $this;
    }

    public function shipmentDate(Carbon $carbonDate = null): static
    {
        $this->shipment->shipment_date = $carbonDate ?? now();
        return $this;
    }

    public function shipmentType(DHLDomesticShipmentType $shipmentType): static
    {
        $this->shipment->product = $shipmentType;
        $this->shipment->delivery_evening = $shipmentType === DHLDomesticShipmentType::EVENING_DELIVERY;
        return $this;
    }

    public function saturdayDelivery(bool $saturday = true): static
    {
        $this->shipment->delivery_on_saturday = $saturday;
        return $this;
    }

    public function saturdayPickup(bool $saturday = true): static
    {
        $this->shipment->pickup_on_saturday = $saturday;
        return $this;
    }

    public function collectOnDelivery(int|float $amount, string $reference = null): static
    {
        DHLIntelligentCostSaver::apply($this->shipment, cod: $amount);
        $this->shipment->collect_on_delivery_reference = $reference;
        return $this;
    }

    public function shipmentValue(int|float $amount): static
    {
        DHLIntelligentCostSaver::apply($this->shipment, $amount);
        return $this;
    }

    public function content(string $content): static
    {
        $this->shipment->content = $content;
        return $this;
    }

    public function comment(string $comment = null): static
    {
        $this->shipment->comment = $comment;
        return $this;
    }

    public function reference(string $reference = null): static
    {
        $this->shipment->reference = $reference;
        return $this;
    }

    public function eveningDelivery(bool $evening = true): static
    {
        $this->shipment->delivery_evening = $evening;
        return $this;
    }

    public function returnOnDelivery(string $reference = null, bool $rod = true): static
    {
        $this->shipment->return_on_delivery = $rod;
        $this->shipment->return_on_delivery_reference = $rod ? $reference : null;
        return $this;
    }

    public function proofOfDelivery(bool $pod = true): static
    {
        $this->shipment->proof_of_delivery = $pod;
        return $this;
    }

    public function selfCollect(bool $selfCollect = true): static
    {
        $this->shipment->self_collect = $selfCollect;
        return $this;
    }

    public function preDeliveryInformation(bool $pdi = true): static
    {
        $this->shipment->predelivery_information = $pdi;
        return $this;
    }

    public function preAviso(bool $preaviso = true): static
    {
        $this->shipment->preaviso = $preaviso;
        return $this;
    }

    public function costCenter(DHLCostCenter $costCenter = null): static
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

    /**
     * @throws DHL24Exception
     */
    public function create()
    {
        $shipmentNumber = (new CreateShipment())->create($this);
        $this->shipment->fill(['number' => $shipmentNumber])->save();
        return $shipmentNumber;
    }

    public function shipmentNumber(string|int $number): static
    {
        $this->shipment->number = $number;
        return $this;
    }

    public function store($quietly = false): DHLShipment
    {
        $quietly
            ? $this->shipment->saveQuietly()
            : $this->shipment->save();

        $this->shipment = DHLShipment::with(['items'])->find($this->shipment->id);
        return $this->shipment;
    }

    /**
     * @throws DHL24Exception
     */
    public function getCost(): float
    {
        $cost = (new Cost())->get($this)->price();
        $this->shipment->update(['cost' => $cost]);
        return $cost;
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
            ->put('shipmentDate', $this->shipment->shipment_date?->format('Y-m-d'))
            ->when(
                $this->shipment->comment,
                fn(Collection $payload) => $payload->put('comment', $this->shipment->comment)
            )
            ->when(
                $this->shipment->reference,
                fn(Collection $payload) => $payload->put('reference', $this->shipment->reference),
                fn(Collection $payload) => $payload->put('reference', $payload->get('service')['collectOnDeliveryReference'] ?? null)
            )
            ->put('content', $this->shipment->content)
            ->put('skipRestrictionCheck', DHLConfig::getRestrictionCheckSetting());

        return $payload->toArray();
    }

    private function getServicesPayload(): ?array
    {
        $services = collect()
            ->put('product', $this->shipment->product?->value)
            ->when(
                $this->shipment->collect_on_delivery,
                fn(Collection $services) => $services
                    ->put('collectOnDelivery', true)
                    ->put('collectOnDeliveryValue', $this->shipment->collect_on_delivery)
                    ->put('collectOnDeliveryForm', 'BANK_TRANSFER'),
            )
            ->when(
                $this->shipment->collect_on_delivery_reference,
                fn(Collection $services) => $services->put('collectOnDeliveryReference', $this->shipment->collect_on_delivery_reference),
                fn(Collection $services) => $services->put('collectOnDeliveryReference', $this->shipment->reference)
            )
            ->when(
                $this->shipment->insurance,
                fn(Collection $services) => $services->put('insurance', true)->put('insuranceValue', $this->shipment->insurance)
            )
            ->when(
                $this->shipment->delivery_evening,
                fn(Collection $services) => $services->put('deliveryEvening', true)
            )
            ->when(
                $this->shipment->pickup_on_saturday,
                fn(Collection $services) => $services->put('pickupOnSaturday', true)
            )
            ->when(
                $this->shipment->delivery_on_saturday,
                fn(Collection $services) => $services->put('deliveryOnSaturday', true)
            )
            ->when(
                $this->shipment->return_on_delivery,
                fn(Collection $services) => $services->put('returnOnDelivery', true)->put('returnOnDeliveryReference', $this->shipment->return_on_delivery_reference)
            )
            ->when(
                $this->shipment->self_collect,
                fn(Collection $services) => $services->put('selfCollect', true)
            )
            ->when(
                $this->shipment->predelivery_information,
                fn(Collection $services) => $services->put('predeliveryInformation', true)
            )
            ->when(
                $this->shipment->preaviso,
                fn(Collection $services) => $services->put('preaviso', true)
            )
            ->when(
                $this->shipment->proof_of_delivery,
                fn(Collection $services) => $services->put('proofOfDelivery', true)
            );

        return $services->toArray();
    }

    public function getPaymentPayload(): array
    {
        $payment = collect([
            'paymentMethod' => 'BANK_TRANSFER',
            'payerType' => $this->payer_type ?? 'SHIPPER',
            'accountNumber' => DHLConfig::getSapNumber(),
        ])
            ->when(
                $this->shipment->cost_center,
                fn(Collection $payment) => $payment->put('costsCenter', $this->shipment->cost_center->name)
            );

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
        if ($this->shipment->shipper_contact_person) $shipper['contactPerson'] = $this->shipment->shipper_contact_person;
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
        if ($this->shipment->receiver_contact_person) $receiver['contactPerson'] = $this->shipment->receiver_contact_person;
        return $receiver;
    }

    private function getPieceListPayload(): array
    {
        $pieceList = $this->shipment->items->map(function (DHLItem $item) {
            $dhlItem = $item->only(['type', 'quantity', 'weight', 'length', 'width', 'height', 'non_standard']);
            foreach ($dhlItem as $prop => $value) {
                if ($value instanceof \BackedEnum) $dhlItem[$prop] = $value->value;
                if (empty($value)) unset($dhlItem[$prop]);
                if ($item->non_standard) $dhlItem['non_standard'] = true;
            }
            return $dhlItem;
        });
        return $pieceList->toArray();
    }
}
