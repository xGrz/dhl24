<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Interfaces\WithLabel;
use xGrz\Dhl24\Traits\HasLabel;

enum DHLReportType: string implements WithLabel
{
    use HasLabel;

    case ALL = 'ALL';
    case EX = 'EX';
    case DR = 'DR';
    case EUROPE = '2EUROPE';
    case PREMIUM = 'PREMIUM';

    public function getLangKey(): string
    {
        return 'reportType';
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }
        return $options;
    }

    /**
     * @throws DHL24Exception
     */
    public static function findByName($reportType): DHLReportType
    {
        foreach (self::cases() as $case) {
            if ($case->name == $reportType) return $case;
        }
        throw new DHL24Exception('Unknown report type [' . $reportType . ']');
    }
}
