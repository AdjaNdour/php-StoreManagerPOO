<?php

namespace App\Model\DTO;

class PaginationModel
{
    private int $page;
    private int $limit;
    private int $totalElements = 0;

    public function __construct(int $page = 1, int $limit = 4)
    {
        $this->page = max(1, $page);
        $this->limit = max(1, $limit);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getCurrentPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = max(1, $page);
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = max(1, $limit);
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    public function getTotalElements(): int
    {
        return $this->totalElements;
    }

    public function setTotalElements(int $totalElements): void
    {
        $this->totalElements = max(0, $totalElements);
    }

    public function getTotalPages(): int
    {
        if ($this->totalElements <= 0) {
            return 1;
        }
        return (int)ceil($this->totalElements / $this->limit);
    }

    public function getStart(): int
    {
        if ($this->totalElements === 0) {
            return 0;
        }
        return $this->getOffset() + 1;
    }

    public function getEnd(): int
    {
        return min($this->getOffset() + $this->limit, $this->totalElements);
    }

    public function hasPrevious(): bool
    {
        return $this->page > 1;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->getTotalPages();
    }
}
