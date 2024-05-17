<?php

namespace xGrz\Dhl24\Api\Structs;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use xGrz\Dhl24\Api\Actions\GetLabel;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Models\DHLShipment;

class Label
{
    private ?DHLShipment $shipment = null;
    private string $filename;
    private string $mimeType;
    private string $content;


    /**
     * @throws DHL24Exception
     */
    public function __construct(DHLShipment|string $shipment)
    {
        self::openShipment($shipment);
        self::loadLabel();
    }

    private function shouldUseLocalStorage(): bool
    {
        return Config::shouldStoreLabels();
    }


    /**
     * @throws DHL24Exception
     */
    private function openShipment(DHLShipment|string $shipment): void
    {
        if ($shipment instanceof DHLShipment) {
            $this->shipment = $shipment;
        } elseif (is_numeric($shipment)) {
            $this->shipment = DHLShipment::where('number', $shipment)->first();
        } else {
            throw new DHL24Exception('Invalid shipment object/number');
        }

        if (is_null($this->shipment)) throw new DHL24Exception('Shipment not found.');
        if ($this->shipment->label) $this->filename = $this->shipment->label;
    }

    /**
     * @throws DHL24Exception
     */
    private function loadLabel(): void
    {
        $this->shipment->label
            ? self::readLabelFromDisk()
            : self::getLabelByApi();
    }

    /**
     * @throws DHL24Exception
     */
    private function readLabelFromDisk(): void
    {
        if (!$this->shipment->label || !self::shouldUseLocalStorage() || !self::isFileStored()) {
            self::getLabelByApi();
            return;
        }

        $this->content = self::getStorage()->get(self::setStorageFilePath());
        $this->mimeType = self::getStorage()->mimeType(self::setStorageFilePath());
    }

    /**
     * @throws DHL24Exception
     */
    private function getLabelByApi(): void
    {
        $label = GetLabel::make($this->shipment->number)->call();
        $this->filename = $label->getFilename();
        $this->mimeType = $label->getMime();
        $this->content = $label->getContent(true);
        $this->store();
    }

    /**
     * @throws DHL24Exception
     */
    private function store(): void
    {
        if (!Config::shouldStoreLabels()) return;

        self::getStorage()->put(self::setStorageFilePath(), $this->content)
            ? $this->shipment->update(['label' => $this->filename])
            : throw new DHL24Exception('Label local store failed');
    }

    private function isFileStored(): bool
    {
        if (!$this->shipment->label) return false;
        return self::getStorage()->exists(self::setStorageFilePath());
    }

    public function download(): Response
    {
        return response($this->content, 200, [
            'Content-Type' => $this->mimeType,
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
        ]);
    }

    public function delete(): bool
    {
        return self::getStorage()->delete(self::setStorageFilePath());
    }

    private function getStorage(): Filesystem
    {
        return Storage::disk(Config::getDiskForLabels());
    }

    private function setStorageFilePath(): string
    {
        return Config::getDirectoryForLabels() . $this->filename;
    }

}
