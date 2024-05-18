<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DHLStatus extends Model
{

    protected $table = 'dhl_statuses';
    protected $keyType = 'string';
    protected $primaryKey = 'symbol';
    public $incrementing = false;
    protected $guarded = [];


    public function shipments(): BelongsToMany
    {
        return $this->belongsToMany(DHLShipment::class, 'dhl_shipment_tracking', 'status', 'shipment_id');
    }
}
