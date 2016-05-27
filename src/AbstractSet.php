<?php

namespace Ramsey\Collection;

/**
 * Class AbstractSet
 * @package Ramsey\Collection
 */
abstract class AbstractSet extends AbstractCollection
{
    /**
     * Adds the specified element to this set if it is not already present (optional operation).
     *
     * @param mixed $element
     * @return bool true if this set did not already contain the specified element
     */
    public function add($element)
    {
        if ($this->contains($element)) {
            return false;
        }
        return parent::add($element);
    }

    public function offsetSet($offset, $value)
    {
        if ($this->contains($value)) {
            return;
        }
        parent::offsetSet($offset, $value);
    }

}
