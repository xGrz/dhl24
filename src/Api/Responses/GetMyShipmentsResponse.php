<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Support\Collection;

class GetMyShipmentsResponse
{
    private Collection $items;

    public function __construct(object $result)
    {
        $this->items = collect($result->getMyShipmentsResult->item);
    }


    public function getItems(): Collection
    {
        return $this->items;
    }
}
