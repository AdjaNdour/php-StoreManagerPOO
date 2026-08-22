<?php

namespace App\Model\DTO;

class FilteredModel
{ 
    private array $filters = [];

    public function __construct(array $initialFilters = [])
    {
        $this->filters = $initialFilters;
    }

    public function setFilter(string $key, mixed $value): void
    {
        $this->filters[$key] = $value;
    }

    public function getFilter(string $key, mixed $default = null): mixed
    {
        return $this->filters[$key] ?? $default;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function hasFilter(string $key): bool
    {
        return !empty($this->filters[$key]);
    }
}
