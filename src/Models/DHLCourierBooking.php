<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DHLCourierBooking extends Model
{
    use SoftDeletes;

    protected $table = 'dhl_courier_bookings';
    protected $guarded = [
        'id'
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(DHLShipment::class, 'courier_booking_id');
    }

}
