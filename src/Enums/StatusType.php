<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Interfaces\WithLabel;
use xGrz\Dhl24\Traits\HasLabel;

enum StatusType: int implements WithLabel
{
    use HasLabel;

    case NOT_FOUND = 0;
    case CREATED = 1;
    case SENT = 10;
    case IN_TRANSPORT = 20;
    case IN_DELIVERY = 40;
    case HOLD = 30;
    case WAITING_TO_BE_PICKED_UP = 50;
    case DELIVERED = 100;
    case PICKED_UP = 101;
    case DELIVERY_FAILED = 300;
    case RETURNED = 400;
    case ERROR = 500;


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

    public function getStateColor(): string
    {
        return match ($this) {
            StatusType::NOT_FOUND, StatusType::ERROR => 'text-red-600',
            StatusType::CREATED => 'text-yellow-200',
            StatusType::SENT => 'text-green-300',
            StatusType::IN_TRANSPORT => 'text-indigo-400',
            StatusType::IN_DELIVERY => 'text-cyan-400',
            StatusType::HOLD => 'text-amber-600',
            StatusType::WAITING_TO_BE_PICKED_UP => '',
            StatusType::DELIVERED, StatusType::PICKED_UP => 'text-green-700',
            StatusType::DELIVERY_FAILED => 'text-orange-500',
            StatusType::RETURNED => 'text-red-500',
            default => 'text-red-200'
        };
    }


}
