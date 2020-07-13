<?php

namespace Dzamyatin\Collection;

use Iterator;

abstract class AbstractCollection implements Iterator
{

    protected array $items = [];

    /**
     * Use it inside while implementing "append" interface requirement method
     *
     * @param $item
     */
    protected function appendElement($item): void
    {
        $this->items[] = $item;
    }

    /**
     * Use it inside while implementing "current" interface requirement method
     *
     * @return void
     */
    protected function currentElement()
    {
        return current($this->items);
    }

    public function key(): int
    {
        return key($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    public function valid() : bool
    {
        return key_exists(key($this->items), $this->items);
    }

}