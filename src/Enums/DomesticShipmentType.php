<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Interfaces\WithLabel;
use xGrz\Dhl24\Traits\HasLabel;

enum DomesticShipmentType: string implements WithLabel
{
    use HasLabel;

    case DOMESTIC = 'AH';
    case PREMIUM = 'PR';
    case DOMESTIC09 = '09';
    case DOMESTIC12 = '12';
    case EVENING_DELIVERY = 'DW';

    public function getLangKey(): string
    {
        return 'shipment.service';
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach(self::cases() as $case) {
            $options[] = [
                'symbol' => $case->value,
                'name' => $case->name,
                'label' => $case->getLabel()
            ];
        }

        return $options;
    }
}
