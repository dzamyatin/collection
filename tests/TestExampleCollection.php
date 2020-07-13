<?php

namespace Tests;

use Dzamyatin\Collection\ExampleCollection;
use Dzamyatin\Collection\ExampleItem;
use PHPUnit\Framework\TestCase;

class TestExampleCollection extends TestCase
{

    public function testFillCollection()
    {
        $exampleCollection = new ExampleCollection();
        $exampleCollection->append(new ExampleItem());
        $exampleCollection->append(new ExampleItem());

        foreach ($exampleCollection as $exampleItem)
        {
            $this->assertInstanceOf(ExampleItem::class, $exampleItem);
        }
    }

    /**
     * @expectedException \TypeError
     */
    public function testExceptionCollection()
    {
        $exampleCollection = new ExampleCollection();
        $exampleCollection->append('it is not an instance of ' . ExampleItem::class);
    }
}