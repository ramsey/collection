<?php
/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://benramsey.com/projects/ramsey-collection/ Documentation
 * @link https://packagist.org/packages/ramsey/collection Packagist
 * @link https://github.com/ramsey/collection GitHub
 */

namespace Ramsey\Collection;

/**
 * This class provides an implementation of the ArrayInterface, to
 * minimize the effort required to implement this interface
 */
abstract class AbstractArray implements ArrayInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Constructs a new array object
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        // Invoke offsetSet() for each value added; in this way, sub-classes
        // may provide additional logic about values added to the array object.
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }

    /**
     * Returns a new iterator from this array
     *
     * @return \ArrayIterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Checks whether the specified offset exists in the array
     *
     * @param mixed $offset
     * @return bool
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns the value stored at the specified offset in the array
     *
     * @param mixed $offset
     * @return mixed
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Sets the specified offset in the map with the given value
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws \InvalidArgumentException
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Removes the specified offset and its value from the map
     *
     * @param mixed $offset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Converts this map object to a string when the object is serialized
     * with `serialize()`
     *
     * @return string
     * @link http://php.net/manual/en/class.serializable.php
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * Re-constructs the object from its serialized form
     *
     * @param string $serialized
     * @link http://php.net/manual/en/serializable.unserialize.php
     */
    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }

    /**
     * Returns the number of elements contained in this array
     *
     * @return int
     * @link http://php.net/manual/en/countable.count.php
     */
    public function count()
    {
        return count($this->data);
    }

    public function clear()
    {
        $this->data = [];
    }

    public function toArray()
    {
        return (array) $this->data;
    }

    public function isEmpty()
    {
        return empty($this->data);
    }
}
