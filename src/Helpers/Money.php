<?php

namespace xGrz\Dhl24\Helpers;

use Illuminate\Support\Number;
use xGrz\Dhl24\Exceptions\MoneyValidationException;

class Money
{
    private int|float $amount = 0;

    /**
     * @throws MoneyValidationException
     */
    private function __construct($amount)
    {
        $amount = is_numeric($amount) ? $amount : $this->toNumeric($amount);
        if (!is_numeric($amount)) throw new MoneyValidationException('Amount is not a number');
        $this->amount = (int)round($amount * 100);
    }

    private function toNumeric($amount): string
    {
        return str($amount)
            ->replace(' ', '')
            ->replace(',', '.')
            ->toString();
    }

    public function toNumber(): float|int
    {
        return round($this->amount / 100, 2);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function format($decimalSeparator = ',', $thousandsSeparator = ''): string
    {
        return str(Number::format($this->amount / 100, 2))
            ->replace('.', '-')
            ->replace(',', $thousandsSeparator)
            ->replace('-', $decimalSeparator)
            ->toString();
    }

    public function multiply($multiplier): static
    {
        $this->amount = round($this->amount * $multiplier);
        return $this;
    }

    public function addPercent($percent): static
    {
        $this->amount += round($this->amount / 100 * $percent);
        return $this;
    }

    public function subtractPercent($percent): static
    {
        $this->amount = ($this->amount / (100 + $percent)) * 100;
        return $this;
    }

    public static function isValid($amount): bool
    {
        try {
            new self($amount);
            return true;
        } catch (MoneyValidationException $e) {
        }
        return false;
    }

    public static function from($amount): static
    {
        return new self($amount);
    }
}
