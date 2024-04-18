<?php

namespace xGrz\Dhl24\Interfaces;

interface WithCombinedAddress
{
    public function getFullAddress(): string;

    public function getFullStreet(): string;

    public function getFullCity(): string;
}
