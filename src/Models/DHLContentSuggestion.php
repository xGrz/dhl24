<?php

namespace xGrz\Dhl24\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use xGrz\Dhl24\Observers\DHLContentSuggestionObserver;

#[ObservedBy(DHLContentSuggestionObserver::class)]
class DHLContentSuggestion extends Model
{
    protected $table = 'dhl_contents';
    protected $guarded = ['id'];
}
