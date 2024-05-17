<?php

namespace xGrz\Dhl24\Api\Responses;

use xGrz\Dhl24\Enums\LabelType;

class GetLabelResponse
{
    private string $shipmentId;
    private string $labelType;
    private string $labelName;
    private string $labelData;
    private string $labelMime;

    public function __construct(object $result)
    {
        $this->shipmentId = $result->getLabelsResult->item->shipmentId;
        $this->labelType = $result->getLabelsResult->item->labelType;
        $this->labelName = $result->getLabelsResult->item->labelName;
        $this->labelData = $result->getLabelsResult->item->labelData;
        $this->labelMime = $result->getLabelsResult->item->labelMimeType;
    }

    public function getShipmentNumber(): string
    {
        return $this->shipmentId;
    }

    public function getType(): LabelType
    {
        return LabelType::tryFrom($this->labelType);
    }

    public function getFilename(): string
    {
        return $this->labelName;
    }

    public function getContent($decoded = true): string
    {
        return $decoded
            ? base64_decode($this->labelData)
            : $this->labelData;
    }

    public function getMime(): string
    {
        return $this->labelMime;
    }

}
