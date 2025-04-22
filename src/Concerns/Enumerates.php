<?php

declare(strict_types=1);

namespace Sikessem\Concerns;

trait Enumerates
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function items(): array
    {
        return array_column(static::cases(), 'value', 'name');
    }

    public static function options(): array
    {
        $items = static::items();

        $options = array_map(fn (string $item) => ['input' => $item, 'output' => __($item)], $items);

        return array_column($options, 'output', 'input');
    }

    public static function array(): array
    {
        return array_combine(static::values(), static::names());
    }
}
