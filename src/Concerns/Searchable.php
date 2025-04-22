<?php

declare(strict_types=1);

namespace Sikessem\Concerns;

use Sikessem\Support\Search;
use Sikessem\Support\SearchResult;

trait Searchable
{
    public static function search(string $query, string $field = 'name', string ...$fields): SearchResult
    {
        return Search::find(static::query(), $query, $field, ...$fields);
    }

    public static function searchWith(mixed $with, string $query, string $field = 'name', string ...$fields): SearchResult
    {
        return Search::find(static::with($with), $query, $field, ...$fields);
    }
}
