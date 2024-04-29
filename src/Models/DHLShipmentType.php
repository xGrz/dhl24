<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DHLShipmentType extends Model
{
    use SoftDeletes;

    protected $table = 'dhl_shipment_types';
    protected $guarded = ['id'];

}
