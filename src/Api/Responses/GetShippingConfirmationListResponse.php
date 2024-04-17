<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GetShippingConfirmationListResponse
{
    private ?string $filename = null;
    private ?string $content = null;
    private ?string $mimeType = null;

    public function __construct(object $result)
    {
        $this->filename = str($result->getPnpResult->fileName)->replaceFirst('pnp_', '')->toString();
        $this->content = $result->getPnpResult->fileData;
        $this->mimeType = $result->getPnpResult->fileMimeType;
    }


    public function store(): bool
    {
        return Storage::disk(self::getDisk())
            // tutaj jest zjebane bo nie ma / pomiedzy direcory a filename
            ->put(self::getFileDirectory() . $this->filename, self::getContent());
    }

    public function download(): BinaryFileResponse
    {
        return response()
            ->download(self::getFileDirectory() . $this->filename, [
                'Content-Type' => $this->mimeType,
            ]);
    }

    private function getDisk()
    {
        return config('dhl24.shipping-confirmations.disk', 'local');
    }

    private function getFileDirectory()
    {
        return config('dhl24.shipping-confirmations.directory', 'dhl/shipping-confirmations');
    }

    private function getContent(): string
    {
        return base64_decode($this->content);
    }
}
