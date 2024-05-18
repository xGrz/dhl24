<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;

class DHLTackingEvent extends Model
{

    protected $table = 'dhl_tracking';
    protected $guarded = ['id'];
    protected $casts = [
        'event_at' => 'datetime',
    ];

//    public function status(): BelongsTo
//    {
//        return $this->belongsTo(DHLStatus::class);
//    }

}
