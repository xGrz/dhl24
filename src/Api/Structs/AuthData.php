<?php

namespace xGrz\Dhl24\Api\Structs;

use xGrz\Dhl24\Facades\Config;

class AuthData
{
    public string $username = '';
    public string $password = '';

    public function __construct() {
        $this->username = Config::getApiUsername();
        $this->password = Config::getApiPassword();
    }
}
