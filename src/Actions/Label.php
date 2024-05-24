<?php

namespace xGrz\Dhl24\Actions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use xGrz\Dhl24\Enums\DHLLabelType;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Models\DHLShipment;

class Label extends ApiCalls
{
    protected string $method = 'GetLabels';
    protected array $payload = [
        'itemsToPrint' => []
    ];

    private $label = [
        'filename' => null,
        'mimeType' => null,
        'content' => null,
    ];

    public function get(DHLShipment|string|int $shipment = null, DHLLabelType $type = null): static
    {
        if ($shipment) self::setShipment($shipment);
        if (!isset($this->payload['itemsToPrint'][0]['labelType']) || $type) $this->setType($type);
        $response = $this->call()?->getLabelsResult;
        if (isset($response->item)) {
            $this->label['filename'] = $response->item->labelName;
            $this->label['mimeType'] = $response->item->labelMimeType;
            $this->label['content'] = $response->item->labelData;
            self::store();
        }
        return $this;
    }

    private function store(): void
    {
        if (!DHLConfig::shouldStoreLabels()) return;
        Storage::disk(DHLConfig::getDiskForLabels())
            ->put(DHLConfig::getDirectoryForLabels() . $this->label['filename'], base64_decode($this->label['content']));
    }

    public function download(): Response
    {
        return response(
            base64_decode($this->label['content']),
            200,
            [
                'Content-Type' => $this->label['mimeType'],
                'Content-Disposition' => 'attachment; filename="' . $this->label['filename'] . '"',
            ]
        );
    }


    public function setType(DHLLabelType $type = null): static
    {
        $this->payload['itemsToPrint'][0]['labelType'] = $type?->value  ?? DHLConfig::getDefaultLabelType()->value;
        return $this;
    }

    public function setShipment(DHLShipment|string|int $shipment): static
    {
        $this->payload['itemsToPrint'][0]['shipmentId'] = $shipment instanceof DHLShipment
            ? $shipment->number
            : $shipment;
        return $this;
    }
}
