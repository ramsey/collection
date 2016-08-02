<?php

namespace Ramsey\Collection;

/**
 * A Set is a Collection that contains no duplicate elements.
 *
 * Great care must be exercised if mutable objects are used as set elements.
 * The behavior of a Set is not specified if the value of an object is changed in a manner
 * that affects equals comparisons while the object is an element in the set.
 *
 * Example usage:
 *
 * ``` php
 * $foo = new \My\Foo();
 * $set = new Set(\My\Foo::class);
 *
 * $set->add($foo); // returns TRUE, the element don't exists
 * $set->add($foo); // returns FALSE, the element already exists
 *
 * $bar = new \My\Foo();
 * $set->add($bar); // returns TRUE, $bar !== $foo
 * ```
 */
class Set extends AbstractSet
{
    /**
     * The type of elements stored in this set
     *
     * A set's type is immutable. For this reason, this property is private
     *
     * @var string
     */
    private $setType;

    /**
     * Constructs a Set object of the specified type,
     * optionally with the specified data
     *
     * @param string $setType
     * @param array $data
     */
    public function __construct($setType, array $data = [])
    {
        $this->setType = $setType;
        parent::__construct($data);
    }

    /**
     * Returns the type associated with this set
     *
     * @return string
     */
    public function getType()
    {
        return $this->setType;
    }
}
