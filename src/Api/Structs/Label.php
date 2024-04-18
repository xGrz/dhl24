<?php

namespace xGrz\Dhl24\Api\Structs;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Interfaces\InteractsWithStorage;

class Label implements InteractsWithStorage
{
    private string $filename;
    private string $mimeType;
    private string $type;
    private string $content;

    public function __construct(object $labelData)
    {
        $this->filename = $labelData->labelName;
        $this->mimeType = $labelData->labelMimeType;
        $this->type = $labelData->labelType;
        $this->content = $labelData->labelData;
    }

    public function store(): static
    {
        Storage::disk(Config::getDiskForLabels())
            ->put(Config::getDirectoryForLabels() . $this->filename, self::getContent());
        return $this;

    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function isFileStored(): bool
    {
        return Storage::disk(Config::getDiskForLabels())
            ->fileExists(Config::getDirectoryForLabels() . $this->filename);

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
