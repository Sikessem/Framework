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
     * Update slug from a Request or FormRequest and optionally fill $data array.
     *
     * @param  class-string<Model>|Model  $entity
     * @param  array<string,mixed>|null  $data
     * @param  string  $name  Column to fill (default: 'slug')
     */
    public static function update(Request|FormRequest $request, string|Model $entity, ?array &$data = null, string $name = 'slug'): null|string|Stringable
    {
        $slug = self::create($request, $entity);

        if ($slug && isset($data)) {
            $data[$name] = $slug;
        }

        return $slug;
    }

    /**
     * Create a slug from a Request/FormRequest or based on model fields.
     *
     * @param  class-string<Model>|Model  $entity
     * @param  string  $name  Column to generate slug for
     */
    public static function create(Request|FormRequest $request, string|Model $entity, string $name = 'slug'): null|string|Stringable
    {
        $slug = null;

        if (
            is_string($entity)
            || ($request->has($name) && $request->$name !== $entity->$name)
            || ($request->has('name') && $request->name !== $entity->$name)
            || ($request->has('title') && $request->title !== $entity->$name)
        ) {
            /** @var string */
            $source = $request->$name ?: $request->name ?: $request->title;
            $slug = self::make($source, $entity, $name);
        }

        return $slug;
    }

    /**
     * Generate a unique slug for a given model and column.
     *
     * Supports:
     *  - PostgreSQL (~ operator)
     *  - MySQL / MariaDB (REGEXP)
     *  - Fallback using LIKE for other databases (SQLite, etc.)
     *
     * @param  string  $base  Source string to slugify
     * @param  class-string<Model>|Model  $entity  Model class or instance
     * @param  string  $column  Column name (default: 'slug')
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

    /**
     * Automatically generate and assign a unique slug for a model instance.
     *
     * Example:
     *  $product = new Product(['name' => 'Fancy Product']);
     *  Slug::auto($product); // assigns $product->slug automatically
     *
     * Can be used in model events:
     *  static::creating(fn($model) => Slug::auto($model));
     *
     * @param  string  $sourceColumn  Column to base slug on (default: 'name')
     * @param  string  $slugColumn  Column to store slug (default: 'slug')
     * @return string Generated unique slug
     */
    public static function auto(Model $model, ?string $source = null, string $sourceColumn = 'name', string $slugColumn = 'slug'): string
    {
        $source ??= $model->$sourceColumn ?? null;

        if (! $source) {
            throw new \InvalidArgumentException("Source column '{$sourceColumn}' is empty.");
        }

        $slug = self::make($source, $model, $slugColumn);

        $model->$slugColumn = $slug;

        return $slug;
    }
}
