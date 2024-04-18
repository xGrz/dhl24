<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Interfaces\InteractsWithStorage;

class GetShippingConfirmationListResponse implements InteractsWithStorage
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

    public function isFileStored(): bool
    {
        return Storage::disk(Config::getDiskForConfirmations())
            ->fileExists(Config::getDirectoryForConfirmations() . $this->filename);
    }

    public function store(): static
    {
        Storage::disk(Config::getDiskForConfirmations())
            ->put(Config::getDirectoryForConfirmations() . $this->filename, self::getContent());
        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function download(bool $shouldBeStored = false): Response
    {
        if ($shouldBeStored) self::store();
        return response(
            self::getContent(),
            200,
            [
                'Content-Type' => $this->mimeType,
                'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
            ]
        );
    }

    private function getContent(): string
    {
        return base64_decode($this->content);
    }
}
