<?php

namespace xGrz\Dhl24\Models;

use Database\Factories\DHLShipmentFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DomesticShipmentType;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Observers\DHLShipmentObserver;

#[ObservedBy(DHLShipmentObserver::class)]
class DHLShipment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'dhl_shipments';

    protected $casts = [
        'service' => 'array',
        'shipment_date' => 'date',
        'receiver_type' => DHLAddressType::class,
        'product' => DomesticShipmentType::class,
    ];

    protected $guarded = [
        'id', 'number'
    ];

    protected static function newFactory()
    {
        return DHLShipmentFactory::new();
    }


    public function cost_center(): BelongsTo
    {
        return $this->belongsTo(DHLCostCenter::class);
    }

    public function courier_booking(): BelongsTo
    {
        return $this->belongsTo(DHLCourierBooking::class);
    }


    private function getShipperPayload(): array
    {
        $shipper = [
            'name' => $this->shipper_name,
            'postalCode' => $this->shipper_postal_code,
            'city' => $this->shipper_city,
            'street' => $this->shipper_street,
            'houseNumber' => $this->shipper_house_number,
        ];
        if ($this->shipper_contact_phone) $shipper['contactPhone'] = $this->shipper_contact_phone;
        if ($this->shipper_contact_email) $shipper['contactEmail'] = $this->shipper_contact_email;
        if ($this->shipper_contact_name) $shipper['contactName'] = $this->shipper_contact_name;
        return $shipper;
    }

    private function getReceiverPayload(): array
    {
        $receiver = [
            'addressType' => $this->receiver_type->value,
            'country' => $this->receiver_country,
            'name' => $this->receiver_name,
            'postalCode' => $this->receiver_postal_code,
            'city' => $this->receiver_city,
            'street' => $this->receiver_street ?? $this->receiver_city,
            'houseNumber' => $this->receiver_house_number,
        ];
        if ($this->receiver_contact_phone) $receiver['contactPhone'] = $this->receiver_contact_phone;
        if ($this->receiver_contact_email) $receiver['contactEmail'] = $this->receiver_contact_email;
        if ($this->receiver_contact_name) $receiver['contactName'] = $this->receiver_contact_name;
        return $receiver;
    }

    private function getPieceListPayload(): array
    {
        $pieceList = $this->items->map(function (DHLItem $item) {
            $dhlItem = $item->only(['type', 'quantity', 'weight', 'length', 'width', 'height']);
            foreach ($dhlItem as $prop => $value) {
                if ($value instanceof \BackedEnum) $dhlItem[$prop] = $value->value;
                if (empty($value)) unset($dhlItem[$prop]);
                if ($item->non_standard) $dhlItem['nonStandard'] = true;
            }
            return $dhlItem;
        });
        return $pieceList->toArray();
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
            ->when($this->shipment_date,
                fn(Collection $payload) => $payload->put('shipmentDate', $this->shipment_date->format('Y-m-d')),
                fn(Collection $payload) => $payload->put('shipmentDate', now()->format('Y-m-d')),
            )
            ->when($this->comment, fn(Collection $payload) => $payload->put('comment', $this->comment))
            ->put('content', $this->content)
            ->put('skipRestrictionCheck', true)
        ;
        return $payload->toArray();
    }

    public function items(): HasMany
    {
        return $this->hasMany(DHLItem::class, 'shipment_id');
    }

    public function getItems(): array
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = match ($item->type) {
                ShipmentItemType::ENVELOPE => ['quantity' => $item->quantity],
                default => [
                    'type' => $item->type->name,
                    'quantity' => $item->quantity,
                    'weight' => $item->weight,
                    'width' => $item->width,
                    'height' => $item->height,
                    'length' => $item->length,
                    'nonStandard' => (bool)$item->non_standard,
                ],
            };
        }
        return $items;
    }

    public function getServicesPayload(): ?array
    {
        $services = collect()
            ->put('product', $this->product->value)
            ->when($this->collectOnDelivery, function (Collection $services) {
                return $services
                    ->put('collectOnDelivery', true)
                    ->put('collectOnDeliveryValue', $this->collect_on_delivery)
                    ->put('collectOnDeliveryForm', 'BANK_TRANSFER');
            })
            ->when($this->collect_on_delivery_reference, function (Collection $services) {
                return $services->put('collectOnDeliveryReference', $this->collect_on_delivery_reference);
            })
            ->when($this->insurance, function (Collection $services) {
                return $services->put('insurance', true)->put('insuranceValue', $this->insurance);
            });

        return $services->toArray();
    }

    public function getPaymentPayload(): array
    {
        $payment = collect([
            'paymentMethod' => 'BANK_TRANSFER',
            'payerType' => $this->payer_type ?? 'SHIPPER',
            'accountNumber' => Config::getSapNumber(),
        ])
            ->when($this->cost_center, fn(Collection $payment) => $payment->put('costsCenter', $this->cost_center->name));

        return $payment->toArray();
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(DHLTackingEvent::class, 'shipment_id', 'id');
    }


}
