<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Model;

class DHLStatus extends Model
{

    protected $table = 'dhl_statuses';
    protected $keyType = 'string';
    protected $primaryKey = 'symbol';
    protected $guarded = [];


}
