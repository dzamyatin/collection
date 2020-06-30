<?php

namespace Dzamyatin\Collection;

class ExampleCollection implements \Iterator
{

    protected array $items = [];

    public function add(ExampleItem $item)
    {
        $this->items[] = $item;
    }

    public function current(): ExampleItem
    {
        return current($this->items);
    }

    public function key(): int
    {
        return key($this->items);
    }

    public function next() : void
    {
        next($this->items);
    }

    public function rewind() : void
    {
        reset($this->items);
    }

    public function valid() : bool
    {
        return key_exists(key($this->items), $this->items);
    }

}