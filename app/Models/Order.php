<?php

namespace App\Models;

class Order
{
    /** @var Item[] */
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
