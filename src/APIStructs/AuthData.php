<?php

namespace xGrz\Dhl24\APIStructs;

use xGrz\Dhl24\Facades\DHLConfig;

class AuthData
{
    public string $username = '';
    public string $password = '';

    public function __construct() {
        $this->username = DHLConfig::getApiUsername();
        $this->password = DHLConfig::getApiPassword();
    }
}
