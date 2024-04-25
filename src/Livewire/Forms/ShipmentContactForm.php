<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;

class ShipmentContactForm extends Form
{
    #[Validate]
    public string $name = '';
    #[Validate]
    public string $phone = '';
    #[Validate]
    public string $email = '';

    public function rules(): array
    {
        return (new StoreShipmentRequest())->getRulesFor('contact');
    }

}
