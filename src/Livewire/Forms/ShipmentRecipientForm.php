<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;

class ShipmentRecipientForm extends Form
{
    #[Validate]
    public string $name = '';
    #[Validate]
    public string $postalCode = '';
    #[Validate]
    public string $city = '';
    #[Validate]
    public string $street = '';
    #[Validate]
    public string $houseNumber = '';


    public function rules(): array
    {
        return (new StoreShipmentRequest())->getRulesForRecipient();
    }

}

