<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLContentSuggestion;

class DHLContentService
{
    private ?DHLContentSuggestion $suggestion = null;

    public function __construct(DHLContentSuggestion|int|null $suggestion)
    {
        if ($suggestion) $this->suggestion = self::loadSuggestion($suggestion);
    }

    public static function query(): Builder
    {
        return DHLContentSuggestion::query()->sorted();
    }

    /**
     * @throws DHL24Exception
     */
    public function add(string $name): static
    {
        try {
            DHLContentSuggestion::create(['name' => $name])->save();
        } catch (UniqueConstraintViolationException $e) {
            throw new DHL24Exception('Content suggestion [' . $name . '] already exists.', 100, $e);
        }
        return $this;
    }

    /**
     * @throws DHL24Exception
     */
    public function rename(string $name): static
    {
        try {
            $this->suggestion->update(['name' => $name]);
        } catch (UniqueConstraintViolationException $e) {
            throw new DHL24Exception('Content suggestion [' . $name . '] already exists.', 100, $e);
        }
        return $this;
    }

    public function delete(): static
    {
        $this->suggestion->delete();
        return $this;
    }

    public function setDefault(): static
    {
        $this->suggestion->update(['is_default' => true]);
        return $this;
    }

    private function loadSuggestion(DHLContentSuggestion|int $suggestion): DHLContentSuggestion
    {
        return $suggestion instanceof DHLContentSuggestion
            ? $suggestion
            : DHLContentSuggestion::find($suggestion);
    }
}
