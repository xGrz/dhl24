<?php

namespace xGrz\Dhl24\Enums;

use xGrz\Dhl24\Interfaces\WithLabel;
use xGrz\Dhl24\Traits\HasLabel;

enum ShipmentItemType: string implements WithLabel
{
    use HasLabel;

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

    public function getDefaultWeight(): ?int
    {
        return match ($this) {
            self::PACKAGE => 1,
            self::PALLET => 100,
            default => null
        };
    }

    public function getDefaultWidth(): ?int
    {
        return match ($this) {
            self::PACKAGE => 15,
            self::PALLET => 80,
            default => null
        };
    }

    public function getDefaultLength(): ?int
    {
        return match ($this) {
            self::PACKAGE => 15,
            self::PALLET => 60,
            default => null
        };
    }

    public function getDefaultHeight(): ?int
    {
        return match ($this) {
            self::PACKAGE => 5,
            self::PALLET => 100,
            default => null
        };
    }

    public function getLangKey(): string
    {
        return 'shipment.type';
    }
}
