<?php

namespace Dzamyatin\Collection;

/**
 * @property ExampleCollection|ExampleItem[] $this
 */
class ExampleCollection extends AbstractCollection
{

    public function append(ExampleItem $item): void
    {
        $this->appendElement($item);
    }

    public function current(): ExampleItem
    {
        return $this->currentElement();
    }
}