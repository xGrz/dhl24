<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use xGrz\Dhl24\Observers\DHLCostCenterObserver;

#[ObservedBy(DHLCostCenterObserver::class)]
class DHLCostCenter extends Model
{
    use SoftDeletes;

    protected $table = 'dhl_cost_centers';
    protected $guarded = ['id'];

}
