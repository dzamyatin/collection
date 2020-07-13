<?php

namespace Dzamyatin\Collection;

class ExampleCollection extends AbstractExtendedCollection
{
    public function first()
    {
        return $this->firstElement();
    }

    public function last()
    {
        return $this->lastElement();
    }

    public function next()
    {
        return $this->nextElement();
    }

    public function current()
    {
        return $this->currentElement();
    }

    public function get($key)
    {
        return $this->getElement($key);
    }

    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    public function add($element)
    {
        $this->elements[] = $element;

        return true;
    }
}