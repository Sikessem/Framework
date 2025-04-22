<?php

declare(strict_types=1);

namespace Sikessem\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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

    public static function make(string $slug, string|Model $entity, string $name = 'slug'): null|string|Stringable
    {
        $slug = Str::of($slug)->slug('-');

        if ($entity::where($name, $slug)->exists()) {
            $count = $entity::whereRaw("$name REGEXP '^{$slug}(-[0-9]*)?$'")->count();
            $slug = $count ? "{$slug}-{$count}" : $slug;
        }

        return $slug;
    }
}
