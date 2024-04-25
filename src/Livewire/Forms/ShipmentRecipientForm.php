<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ShipmentRecipientForm extends Form
{
    #[Validate('required|string|max:60')]
    public string $name = '';
    #[Validate('required|string|max:10')]
    public string $postalCode = '';
    #[Validate('required|string|max:17')]
    public string $city = '';
    #[Validate('required|string|max:35')]
    public string $street = '';
    #[Validate('required|string|max:10')]
    public string $houseNumber = '';
}
