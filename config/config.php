<?php

use xGrz\Dhl24\Enums\LabelType;

return [
    'shipment-insurance' => [
        'insurance_value_round_up' => 0,

        /*
         * This is for domestic shipment with value < 500PLN.
         * By law regulations defaults all those shipments are insured by default without charging
         * When intelligent cost saver is set to true insurance will be removed in that case.
         */
        'intelligent_cost_saver' => true,
        'intelligent_cost_saver_max_value' => 500,
    ],
    'shipping-confirmation' => [
        'disk' => 'local',
        'directory' => 'dhl/shipping-confirmations',
        'defaultType' => '',
    ],
    'labels' => [
        'disk' => 'local',
        'directory' => 'dhl/shipment-labels',
        'defaultType' => LabelType::BLP->name,
    ],
    'auth' => [
        'wsdl' => env('DHL24_WSDL'),
        'username' => env('DHL24_USERNAME'),
        'password' => env('DHL24_PASSWORD'),
        'sap' => env('DHL24_SAP', ''),
    ]

];
