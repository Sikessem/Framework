<?php

declare(strict_types=1);

namespace Sikessem\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Search
{
    public static function find(Builder $builder, string $query, string $field = 'name', string ...$fields): SearchResult
    {
        $query = trim($query);
        $query = preg_replace('/(\s+)/', '%', $query) ?: $query;
        $query = Str::ascii($query);

        if (DB::connection()->getDriverName() === 'pgsql') {
            $builder = $builder->where(DB::raw("unaccent($field)"), 'ilike', "%$query%");

            foreach ($fields as $field) {
                $builder = $builder->orWhere(DB::raw("unaccent($field)"), 'ilike', "%$query%");
            }
        } else {
            $builder = $builder->where($field, 'LIKE', "%{$query}%");
            $builder = $builder->orWhereRaw("CONVERT({$field} USING utf8) COLLATE utf8_general_ci LIKE '%{$query}%'");

            foreach ($fields as $field) {
                $builder = $builder->orWhere($field, 'LIKE', "%{$query}%");
                $builder = $builder->orWhereRaw("CONVERT({$field} USING utf8) COLLATE utf8_general_ci LIKE '%{$query}%'");
            }
        }

        return self::result($builder, $query, $field, ...$fields);
    }

    public static function result(Builder $builder, string $query, string $field = 'name', string ...$fields): SearchResult
    {
        return new SearchResult($builder, $query, $field, ...$fields);
    }
}
