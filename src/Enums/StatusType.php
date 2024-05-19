<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Interfaces\WithLabel;
use xGrz\Dhl24\Traits\HasLabel;

enum StatusType: int implements WithLabel
{
    use HasLabel;

    case NEW = 1;
    case PICKED_UP = 10;
    case ARRIVED = 20;
    case SORTING = 30;
    case DEPARTED = 40;
    case OUT_FOR_DELIVERY = 50;
    case DELIVERED = 100;
    case HOLD = 200;
    case FAILED = 210;
    case RETURNED = 220;


    public function getLangKey(): string
    {
        return 'shipment.statusType';
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }
        return $options;
    }
}
