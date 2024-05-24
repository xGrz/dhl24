<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Exceptions\DHL24Exception;

enum DHLLabelType: string
{
    case LP = 'LP';
    case BLP = 'BLP';
    case LBLP = 'LBLP';
    case ZBLP = 'ZBLP';
    case ZBLP300 = 'ZBLP300';


    /**
     * @throws DHL24Exception
     */
    public static function findByName(string $name): DHLLabelType
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) return $case;
        }
        throw new DHL24Exception('Unknown label type: ' . $name);
    }
}
