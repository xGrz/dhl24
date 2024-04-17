<?php

namespace xGrz\Dhl24\Api\Structs;

class AuthData
{
    public $username = '';
    public $password = '';

    public function __construct() {
        $this->username = env('DHL24_USER');
        $this->password = env('DHL24_PASSWORD');
    }
}
