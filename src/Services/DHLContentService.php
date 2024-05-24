<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\UniqueConstraintViolationException;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLContentSuggestion;

class DHLContentService
{
    public static function getContents(): EloquentCollection
    {
        return DHLContentSuggestion::sorted()->get();
    }

    /**
     * @throws DHL24Exception
     */
    public static function add(string $name)
    {
        try {
            return DHLContentSuggestion::create(['name' => $name])->save();
        } catch (UniqueConstraintViolationException $e) {
            throw new DHL24Exception('Content suggestion [' . $name . '] already exists.', 100, $e);
        }
    }

    /**
     * @throws DHL24Exception
     */
    public static function rename(DHLContentSuggestion|int $suggestion, string $name): bool
    {
        try {
            return self::suggestion($suggestion)
                ->update(['name' => $name]);
        } catch (UniqueConstraintViolationException $e) {
            throw new DHL24Exception('Content suggestion [' . $name . '] already exists.', 100, $e);
        }
    }

    public static function delete(DHLContentSuggestion|int $suggestion): ?bool
    {
        return self::suggestion($suggestion)
            ->delete();
    }

    public static function setDefault(DHLContentSuggestion|int $suggestion): bool
    {
        return self::suggestion($suggestion)
            ->update(['is_default' => true]);

    }

    private static function suggestion(DHLContentSuggestion|int $suggestion): DHLContentSuggestion
    {
        return $suggestion instanceof DHLContentSuggestion
            ? $suggestion
            : DHLContentSuggestion::find($suggestion);
    }
}
