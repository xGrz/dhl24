<?php

namespace xGrz\Dhl24\Wizard\Components;

use xGrz\Dhl24\Enums\PayerType;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Traits\Arrayable;

class PaymentData
{
    use Arrayable;

    public string $paymentMethod = 'BANK_TRANSFER';
    public string $payerType;
    public ?string $accountNumber = null;
    public ?string $costsCenter = null;

    public function __construct(PayerType $payerType = PayerType::SHIPPER, ?string $costsCenter = null)
    {
        $this->payerType = $payerType->name;
        $this->accountNumber = Config::getSapNumber();
        if (!empty($costsCenter)) $this->costsCenter = $costsCenter;
    }

    public function setCostCenter(string $costsCenter): static
    {
        $this->costsCenter = $costsCenter;
        return $this;
    }

    public function setPayerType(PayerType $payerType): static
    {
        $this->payerType = $payerType->name;
        return $this;
    }
}