<?php

namespace xGrz\Dhl24\Enums;

enum ShipmentItemType: string
{
    case ENVELOPE = 'envelope';
    case PACKAGE = 'package';
    case PALLET = 'pallet';

    public function getAttributes(): array
    {
        return match ($this) {
            self::PACKAGE, self::PALLET => ['width', 'height', 'length', 'weight', 'nonStandard'],
            default => []
        };
    }

    public static function findByName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === strtoupper($name)) return $case;
        }
        return null;
    }
}
