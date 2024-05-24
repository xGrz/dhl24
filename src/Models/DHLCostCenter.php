<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use xGrz\Dhl24\Observers\DHLCostCenterObserver;

#[ObservedBy(DHLCostCenterObserver::class)]
class DHLCostCenter extends Model
{
    use SoftDeletes;

    protected $table = 'dhl_cost_centers';
    protected $guarded = ['id'];
    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function scopeSorted(Builder $query): void
    {
        $query->orderBy('is_default', 'DESC')->orderBy('name');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(DHLShipment::class, 'cost_center_id');
    }

}
