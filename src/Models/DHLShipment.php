<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DHLShipment extends Model
{
    use SoftDeletes;

    protected $table = 'dhl_shipments';

    protected $casts = [
        'shipper' => 'array',
        'receiver' => 'array',
        'piece_list' => 'array',
        'service' => 'array',
        'payment' => 'array',
        'shipment_date' => 'date',
    ];

    protected $guarded = [
        'id', 'shipment_id'
    ];

    public function courier_booking(): BelongsTo
    {
        return $this->belongsTo(DHLCourierBooking::class);
    }
}
