<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use xGrz\Dhl24\Enums\DHLStatusType;

/**
 * @property DHLTracking $pivot
 */
class DHLStatus extends Model
{

    protected $table = 'dhl_statuses';
    protected $keyType = 'string';
    protected $primaryKey = 'symbol';
    public $incrementing = false;
    protected $guarded = [];
    protected $casts = [
        'type' => DHLStatusType::class,
    ];

    public function scopeOrderByTypes(Builder $query): void
    {
        $query->orderBy('type');
    }

    public function scopeFinishedState(Builder $query): void
    {
        $query->whereBetween('type', [100, 200]);
    }

    public function getDescription(): string
    {
        return $this->custom_description ?? $this->description;
    }

    public function shipments(): BelongsToMany
    {
        return $this->belongsToMany(DHLShipment::class, 'dhl_shipment_tracking', 'status', 'shipment_id');
    }
}
