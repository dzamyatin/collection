<?php

namespace Dzamyatin\Collection;

use ArrayIterator;
use Closure;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use const ARRAY_FILTER_USE_BOTH;
use Doctrine\Common\Collections\{Collection, Criteria, Selectable};
use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_reverse;
use function array_search;
use function array_slice;
use function array_values;
use function count;
use function current;
use function end;
use function in_array;
use function key;
use function next;
use function reset;
use function spl_object_hash;
use function uasort;

abstract class AbstractExtendedCollection implements Collection, Selectable
{

    protected array $elements = [];

    protected function firstElement()
    {
        return reset($this->elements);
    }

    protected function lastElement()
    {
        return end($this->elements);
    }

    protected function nextElement()
    {
        return next($this->elements);
    }

    protected function currentElement()
    {
        return current($this->elements);
    }

    protected function getElement($key)
    {
        return $this->elements[$key] ?? null;
    }

    protected function setElement($key, $value)
    {
        $this->elements[$key] = $value;
    }

    protected function addElement($element)
    {
        $this->elements[] = $element;

        return true;
    }

    protected function toArray()
    {
        return $this->elements;
    }

    protected function createFrom(array $elements)
    {
        return new static($elements);
    }

    public function key(): int
    {
        return key($this->elements);
    }

    public function remove($key)
    {
        if (! isset($this->elements[$key]) && ! array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    public function removeElement($element)
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (! isset($offset)) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function containsKey($key)
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    public function exists(Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    public function getKeys()
    {
        return array_keys($this->elements);
    }

    public function getValues()
    {
        return array_values($this->elements);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function isEmpty()
    {
        return empty($this->elements);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    public function map(Closure $func)
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    public function filter(Closure $p)
    {
        return $this->createFrom(array_filter($this->elements, $p, ARRAY_FILTER_USE_BOTH));
    }

    public function forAll(Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if (! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    public function partition(Closure $p)
    {
        $matches = $noMatches = [];

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    public function __toString()
    {
        return self::class . '@' . spl_object_hash($this);
    }

    public function clear()
    {
        $this->elements = [];
    }

    public function slice($offset, $length = null)
    {
        return array_slice($this->elements, $offset, $length, true);
    }

    public function matching(Criteria $criteria)
    {
        $expr     = $criteria->getWhereExpression();
        $filtered = $this->elements;

        if ($expr) {
            $visitor  = new ClosureExpressionVisitor();
            $filter   = $visitor->dispatch($expr);
            $filtered = array_filter($filtered, $filter);
        }

        $orderings = $criteria->getOrderings();

        if ($orderings) {
            $next = null;
            foreach (array_reverse($orderings) as $field => $ordering) {
                $next = ClosureExpressionVisitor::sortByField($field, $ordering === Criteria::DESC ? -1 : 1, $next);
            }

            uasort($filtered, $next);
        }

        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();

        if ($offset || $length) {
            $filtered = array_slice($filtered, (int) $offset, $length);
        }

        return $this->createFrom($filtered);
    }
}
