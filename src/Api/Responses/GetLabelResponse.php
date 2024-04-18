<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\Dhl24\Api\Structs\Label;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class GetLabelResponse
{
    private array $labels = [];

    public function __construct(object $result)
    {
        $labels = $result->getLabelsResult->item;
        if (is_array($labels)) {
            foreach ($labels as $labelData) {
                $this->labels[] = new Label($labelData);
            }
        } else {
            $this->labels[] = new Label($labels);
        }
    }

    public function store(): static
    {
        foreach ($this->labels as $label) {
            $label->store();
        }
        return $this;
    }

    /**
     * @throws DHL24Exception
     */
    public function download(bool $shouldBeStored = false): Response
    {
        if (count($this->labels) === 1) $this->labels[0]->download($shouldBeStored);
        throw new DHL24Exception('Download method is available only for single shipmentId.');
    }


}
