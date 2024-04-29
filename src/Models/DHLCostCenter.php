<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DHLCostCenter extends Model
{
    use SoftDeletes;

    protected $table = 'dhl_cost_centers';
    protected $guarded = ['id'];

}
