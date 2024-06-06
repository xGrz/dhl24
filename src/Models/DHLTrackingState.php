<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use xGrz\Dhl24\Enums\DHLStatusType;

/**
 * @property DHLTracking $pivot
 */
class DHLTrackingState extends Model
{

    protected $table = 'dhl_tracking_states';
    protected $keyType = 'string';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $guarded = [];
    protected $casts = [
        'type' => DHLStatusType::class,
    ];

    public function scopeOrderByTypes(Builder $query): void
    {
        $query
            ->orderBy('type')
            ->orderBy('code');
    }

    public function scopeFinishedState(Builder $query): void
    {
        $query->whereBetween('type', [100, 200]);
    }

    public function label(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $this->description ?? $this->system_description,
            set: fn(?string $value) => $this->description = $value,
        );
    }

    public function shipments(): BelongsToMany
    {
        return $this->belongsToMany(DHLShipment::class, 'dhl_shipment_tracking', 'code', 'shipment_id');
    }
}
