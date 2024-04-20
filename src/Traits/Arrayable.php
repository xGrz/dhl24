<?php

namespace xGrz\Dhl24\Traits;

trait Arrayable
{
    private function convertObjectToArray(string $key, mixed $value, array &$dataCollector): array
    {
        if (!is_object($value)) return $dataCollector;

        if (method_exists($value, 'toArray')) {
            $dataCollector[$key] = $value->toArray();
        } else {
            $dataCollector[$key] = $value;
        }

        return $dataCollector;
    }

    private function removeNullValues(string $key, mixed $value, array &$dataCollector): array
    {
        if (is_null($value)) return $dataCollector;
        if (is_array($value) && empty($value)) return $dataCollector;
        $dataCollector[$key] = $value;
        return $dataCollector;
    }

    private function makeArray(): array
    {
        $output = [];
        foreach ($this as $key => $value) {
            if (is_object($value)) {
                self::convertObjectToArray($key, $value, $output);
            } else {
                self::removeNullValues($key, $value, $output);
            }


        }
        return $output;
    }

    public function toArray(): array
    {
        return self::makeArray();
    }

}
