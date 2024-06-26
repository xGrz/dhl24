<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use xGrz\Dhl24\Casts\PostalCodeCast;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLPayerType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Observers\DHLShipmentObserver;

#[ObservedBy(DHLShipmentObserver::class)]
class DHLShipment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'dhl_shipments';

    protected $casts = [
        'service' => 'array',
        'shipment_date' => 'datetime:Y-m-d',
        'shipper_postal_code' => PostalCodeCast::class,
        'receiver_postal_code' => PostalCodeCast::class,
        'receiver_type' => DHLAddressType::class,
        'product' => DHLDomesticShipmentType::class,
        'payer_type' => DHLPayerType::class,
    ];

    protected $guarded = [
        'id'
    ];

    protected $attributes = [
        'receiver_type' => DHLAddressType::CONSUMER,
        'pickup_on_saturday' => false,
        'delivery_on_saturday' => false,
        'preaviso' => false,
        'predelivery_information' => false,
        'is_packstation' => false,
        'is_postfiliale' => false,
        'product' => DHLDomesticShipmentType::DOMESTIC,
        'payer_type' => DHLPayerType::SHIPPER,
    ];


    public function scopeWithDetails(Builder $query): void
    {
        $query->with(self::getRelationsListForDetails());
    }

    public static function getRelationsListForDetails(): array
    {
        return [
            'items',
            'courier_booking',
            'cost_center',
            'tracking'
        ];
    }

    public function cost_center(): BelongsTo
    {
        return $this->belongsTo(DHLCostCenter::class);
    }

    public function courier_booking(): BelongsTo
    {
        return $this->belongsTo(DHLCourierBooking::class);
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
                DHLShipmentItemType::ENVELOPE => ['quantity' => $item->quantity],
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

    public function tracking(): BelongsToMany
    {
        return $this->belongsToMany(DHLTrackingState::class, 'dhl_shipment_tracking', 'shipment_id', 'code_id')
            ->withPivot(['terminal', 'event_timestamp'])
            ->orderByPivot('event_timestamp', 'desc')
            ->using(DHLTracking::class);
    }

    public function isExpress(): bool
    {
        $this->loadMissing('items');
        $overWeight = $this->items->map(fn($item) => $item->weight)->max() >= 30;
        $hasPallet = $this->items->filter(fn($item) => $item->type === DHLShipmentItemType::PALLET)->count();
        return !($hasPallet || $overWeight);
    }

    public function isBookingAvailable(): bool
    {
        if ($this->courier_booking_id) return false;

        $lastTrackingStatus = $this->tracking->first();
        return is_null($lastTrackingStatus)
            || $lastTrackingStatus->type === DHLStatusType::CREATED;
    }

    public function canBeDeleted(): bool
    {
        return self::isBookingAvailable();
    }


}
