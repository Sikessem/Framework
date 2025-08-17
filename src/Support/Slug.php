<?php

declare(strict_types=1);

namespace Sikessem\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Slug
{
    /**
     * @param  array<string,mixed>  $data
     */
    public static function update(Request|FormRequest $request, string|Model $entity, ?array &$data = null, string $name = 'slug'): null|string|Stringable
    {
        $slug = self::create($request, $entity);
        if ($slug && isset($data)) {
            $data[$name] = $slug;
        }

        return $slug;
    }

    public static function create(Request|FormRequest $request, string|Model $entity, string $name = 'slug'): null|string|Stringable
    {
        $slug = null;

        if (
            is_string($entity)
            || ($request->has($name) && $request->$name !== $entity->$name)
            || $request->has('name') && $request->name !== $entity->$name
            || $request->has('title') && $request->title !== $entity->$name
        ) {
            /** @var string */
            $slug = ($request->$name ?: $request->name) ?: $request->$name;
            $slug = self::make($slug, $entity, $name);
        }

        return $slug;
    }

    /**
     * Generate a unique slug for a given model and column.
     *
     * Supports:
     *  - PostgreSQL (~ operator)
     *  - MySQL / MariaDB (REGEXP)
     *  - Fallback using LIKE for other databases
     *
     * @param  class-string<Model>|Model  $entity
     */
    public static function make(string $base, string|Model $entity, string $column = 'slug'): null|string|Stringable
    {
        $slug = Str::slug($base, '-');

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = is_string($entity) ? $entity::query() : $entity->newQuery();

        if ($query->where($column, $slug)->doesntExist()) {
            return $slug;
        }

        $pattern = '^'.preg_quote($slug, '/').'(-[0-9]+)?$';
        $driver = DB::connection()->getDriverName();

        $query = match ($driver) {
            'pgsql' => $query->whereRaw("\"$column\" ~ ?", [$pattern]),
            'mysql', 'mariadb' => $query->whereRaw("`$column` REGEXP ?", [$pattern]),
            default => $query->where($column, $slug)->orWhere($column, 'LIKE', $slug.'-%'),
        };

        $results = $query->pluck($column);
        $max = 0;
        $regex = '/^'.preg_quote($slug, '/').'-(\d+)$/';

        foreach ($results as $result) {
            if (preg_match($regex, $result, $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }

        return $slug.'-'.($max + 1);
    }
}
