<?php

namespace xGrz\Dhl24\Models;

use Database\Factories\DHLItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use xGrz\Dhl24\Enums\ShipmentItemType;

class DHLItem extends Model
{
    use HasFactory;

    protected $table = 'dhl_shipment_items';
    protected $guarded = ['id'];

    protected $casts = [
        'type' => ShipmentItemType::class,
        'non_standard' => 'boolean',
    ];

    protected static function newFactory(): DHLItemFactory
    {
        return DHLItemFactory::new();
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(DHLShipment::class);
    }

    public function getWeight(): string
    {
        if (!$this->weight) return '';
        return $this->weight . 'kg';
    }

    public function getDiamentions(): string
    {
        if(!$this->width) return '';
        return join('x', [$this->width, $this->height, $this->length]) . 'cm';
    }

}
