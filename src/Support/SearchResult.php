<?php

declare(strict_types=1);

namespace Sikessem\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as IlluminateCollection;

class SearchResult
{
    protected array $fields = [];

    public function __construct(protected Builder $builder, protected string $query, string $field, string ...$fields)
    {
        $this->fields = [$field, ...$fields];
    }

    public function builder(): Builder
    {
        return $this->builder;
    }

    public function query(): string
    {
        return $this->query;
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function get(): Collection
    {
        return $this->builder()->get()->unique();
    }

    public function toArray(): array
    {
        return $this->get()->toArray();
    }

    public function limit(int $limit): Builder
    {
        return $this->builder()->limit($limit);
    }

    public function pluck(string $field = 'name', string $id = 'id', int $limit = 50): IlluminateCollection
    {
        return $this->limit($limit)->pluck($field, $id);
    }

    public function pluckToArray(string $field = 'name', string $id = 'id', int $limit = 50): array
    {
        return $this->pluck($field, $id, $limit)->toArray();
    }
}
