<?php

namespace xGrz\Dhl24\Api\Structs\ServicePoint;

class WorkingHours
{
    public array $monday = [
        'open' => null,
        'close' => null,
    ];
    public array $tuesday = [
        'open' => null,
        'close' => null,
    ];
    public array $wednesday = [
        'open' => null,
        'close' => null,
    ];
    public array $thursday = [
        'open' => null,
        'close' => null,
    ];
    public array $friday = [
        'open' => null,
        'close' => null,
    ];
    public array $saturday = [
        'open' => null,
        'close' => null,
    ];
    public array $sunday = [
        'open' => null,
        'close' => null,
    ];

    public function __construct(object $servicePoint)
    {
        $this->setWorkingHours('monday', $servicePoint->monOpen, $servicePoint->monClose)
            ->setWorkingHours('tuesday', $servicePoint->tueOpen, $servicePoint->tueClose)
            ->setWorkingHours('wednesday', $servicePoint->wedOpen, $servicePoint->wedClose)
            ->setWorkingHours('thursday', $servicePoint->thuOpen, $servicePoint->thuClose)
            ->setWorkingHours('friday', $servicePoint->friOpen, $servicePoint->friClose)
            ->setWorkingHours('saturday', $servicePoint->satOpen, $servicePoint->satClose)
            ->setWorkingHours('sunday', $servicePoint->sunOpen, $servicePoint->sunClose);
    }

    private function setWorkingHours(string $day, string|null $openFrom, string|null $closeAt): static
    {
        $this->$day = [
            'open' => $openFrom,
            'close' => $closeAt,
        ];
        return $this;
    }

    public function isNonstopOpen(): bool {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            if ($this->$day['open'] !== '00:00') return false;
            if ($this->$day['close'] !== '23:59') return false;
        }
        return true;
    }

}
