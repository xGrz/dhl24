<?php

namespace xGrz\Dhl24\Api\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use xGrz\Dhl24\Enums\ShippingConfirmationType;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Services\ConfigService;

class DailyShippingConfirmationList
{
    private string $fileName;
    private string $fileMimeType;
    private string $fileData;
    private string $error = '';

    /**
     * @throws DHL24Exception
     */
    public function __construct(public ?Carbon $date = null, public ShippingConfirmationType $type = ShippingConfirmationType::ALL)
    {
        if (!$this->date) $this->date = now();
        $this->call();
    }

    /**
     * @throws DHL24Exception
     */
    private function call(): void
    {
        $payload = [
            'pnpRequest' => [
                'authData' => [
                    'username' => env('DHL24_USER'),
                    'password' => env('DHL24_PASSWORD'),
                ],
                'date' => $this->date->format('Y-m-d'),
                'type' => $this->type->name
            ],
        ];
        try {
            $response = (new ConfigService())->connection()->getPnp($payload)->getPnpResult;
            $this->fileName = $response->fileName;
            $this->fileMimeType = $response->fileMimeType;
            $this->fileData = $response->fileData;
        } catch (\SoapFault $e) {
            throw new DHL24Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getDocument(): string
    {
        return base64_decode($this->fileData);
    }

    public function getMime(): string
    {
        return $this->fileMimeType;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function store(): bool
    {
        return Storage::disk('local')->put($this->getFileName(), $this->getDocument());
    }

    public function getFilePath(): string
    {
        if (!Storage::disk('local')->exists($this->fileName)) $this->store();
        return Storage::disk('local')->path($this->getFileName());
    }

    public function download(): BinaryFileResponse
    {
        return response()->download($this->getFilePath(), $this->getFileName(), [
            'Content-Type' => $this->fileMimeType,
        ]);
    }
}
