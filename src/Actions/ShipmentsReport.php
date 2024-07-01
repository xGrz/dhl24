<?php

namespace xGrz\Dhl24\Actions;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use xGrz\Dhl24\Enums\DHLReportType;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHLConfig;

class ShipmentsReport extends ApiCalls
{
    protected string $method = 'getPnp';
    protected string $wrapper = 'pnpRequest';
    protected array $payload = [
        'date' => '',
        'type' => 'ALL'
    ];

    protected array $report = [
        'filename' => null,
        'mimeType' => null,
        'content' => null
    ];

    /**
     * @throws DHL24Exception
     */
    public function get()
    {
        $response = $this->call()?->getPnpResult;
        if (isset($response?->fileName)) {
            $this->report['filename'] = $response->fileName;
            $this->report['mimeType'] = $response->fileMimeType;
            $this->report['content'] = $response->fileData;
            self::store();
        }
        return $this;
    }

    private function store(): void
    {
        if (!DHLConfig::shouldStoreReports()) return;
        Storage::disk(DHLConfig::getDiskForReports())
            ->put(DHLConfig::getDirectoryForReports() . $this->report['filename'], base64_decode($this->report['content']));
    }

    public function setDate(Carbon $date): static
    {
        $this->payload['date'] = $date->format('Y-m-d');
        return $this;
    }

    public function setType(DHLReportType $type): static
    {
        $this->payload['type'] = $type->value;
        return $this;
    }

    public function download(): Response
    {
        return response(
            base64_decode($this->report['content']),
            200,
            [
                'Content-Type' => $this->report['mimeType'],
                'Content-Disposition' => 'attachment; filename="' . $this->report['filename'] . '"',
            ]
        );
    }

    public function getResponse(): array
    {
        return $this->report;
    }

}
