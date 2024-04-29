<?php

namespace xGrz\Dhl24\Traits;

trait HasLabel
{
    public function getLabel(): string
    {
        return __('dhl::' . self::getLangKey() . '.' . $this->name);
    }

}
