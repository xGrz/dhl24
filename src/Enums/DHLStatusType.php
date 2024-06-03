<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Interfaces\WithLabel;
use xGrz\Dhl24\Traits\HasLabel;

enum DHLStatusType: int implements WithLabel
{
    use HasLabel;

    // ALL STATES BETWEEN 100 and 200 pointing to DELIVERED state.

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
        return 'state';
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
            DHLStatusType::NOT_FOUND, DHLStatusType::ERROR => 'text-red-600',
            DHLStatusType::CREATED => 'text-yellow-200',
            DHLStatusType::SENT => 'text-green-300',
            DHLStatusType::IN_TRANSPORT => 'text-indigo-400',
            DHLStatusType::IN_DELIVERY => 'text-cyan-400',
            DHLStatusType::HOLD => 'text-amber-600',
            DHLStatusType::WAITING_TO_BE_PICKED_UP => '',
            DHLStatusType::DELIVERED, DHLStatusType::PICKED_UP => 'text-green-700',
            DHLStatusType::DELIVERY_FAILED => 'text-orange-500',
            DHLStatusType::RETURNED => 'text-red-500',
            default => 'text-red-200'
        };
    }

    /**
     * @throws DHL24Exception
     */
    public static function findByName($statusName): DHLStatusType
    {
        foreach (self::cases() as $case) {
            if ($case->name == $statusName) return $case;
        }
        throw new DHL24Exception('Unknown state [' . $statusName . ']');
    }

}
