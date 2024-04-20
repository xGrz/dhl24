<?php

namespace xGrz\Dhl24\Wizard\Components;

use xGrz\Dhl24\Traits\Arrayable;

class PieceList
{
    use Arrayable;

    private array $listing = [];


    public function add(Item $item): static
    {
        $this->listing[] = $item;
        return $this;
    }

    public function getCount(): int
    {
        return count($this->listing);
    }

    public function toArray(): array
    {
        return self::makeArray($this->listing);
    }

}
