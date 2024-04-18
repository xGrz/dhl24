<?php

return [
    'shipping-confirmation' => [
        'disk' => 'local',
        'directory' => 'dhl/shipping-confirmations',
        'defaultType' => '',
    ],
    'labels' => [
        'disk' => 'local',
        'directory' => 'dhl/shipment-labels',
        'defaultType' => ''
    ],
    'auth' => [
        'wsdl' => env('DHL24_WSDL'),
        'username' => env('DHL24_USERNAME'),
        'password' => env('DHL24_PASSWORD'),
    ]

];
