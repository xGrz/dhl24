<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DHLTracking extends Pivot
{
    protected $table = 'dhl_shipment_tracking';

    protected $casts = [
        'event_timestamp' => 'datetime'
    ];

}
