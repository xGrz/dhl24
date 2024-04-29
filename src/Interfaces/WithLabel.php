<?php

namespace xGrz\Dhl24\Interfaces;

interface WithLabel
{
    public function getLangKey(): string;

    public function getLabel(): string;
}
