<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;

class ShipmentRecipientForm extends Form
{
    #[Validate]
    public string $name = 'Tester Testowski';
    #[Validate]
    public string $postalCode = '02-777';
    #[Validate]
    public string $city = 'Warszawa';
    #[Validate]
    public string $street = 'Wąwozowa';
    #[Validate]
    public string $houseNumber = '20';


    public function rules(): array
    {
        return (new StoreShipmentRequest())->getRulesForRecipient();
    }

}

