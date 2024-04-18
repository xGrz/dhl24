<?php

namespace xGrz\Dhl24\Interfaces;

use Illuminate\Http\Response;

interface InteractsWithStorage
{
    public function store(): static;

    public function isFileStored(): bool;

    public function download(bool $shouldBeStored = false): Response;
}
