<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ShipmentContactForm extends Form
{
    #[Validate('nullable|string|max:60')]
    public string $name = '';
    #[Validate('nullable|string|max:20')]
    public string $phone = '';
    #[Validate('nullable|email|max:60')]
    public string $email = '';
}
