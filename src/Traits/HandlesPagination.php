<?php

namespace xGrz\Dhl24\Traits;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HandlesPagination
{
    protected static function applyPagination(Builder|HasMany $query, bool|int $withPagination = false, string $paginationName = null): Collection|LengthAwarePaginator
    {
        return match ($withPagination) {
            true => $query->paginate(pageName: $paginationName),
            false => $query->get(),
            default => $query->paginate($withPagination, pageName: $paginationName)
        };
    }
}
