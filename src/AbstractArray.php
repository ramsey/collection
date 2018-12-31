<?php
/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://github.com/ramsey/collection GitHub
 */

declare(strict_types=1);

namespace Ramsey\Collection;

/**
 * This class provides a basic implementation of `ArrayInterface`, to minimize
 * the effort required to implement this interface.
 */
abstract class AbstractArray implements ArrayInterface
{
    /**
     * The items of this array.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructs a new array object.
     *
     * @param array $data The initial items to add to this array.
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
     * Returns an iterator for this array.
     *
     * @return \Traversable
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php IteratorAggregate::getIterator()
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Returns `true` if the given offset exists in this array.
     *
     * @param mixed $offset The offset to check.
     *
     * @return bool
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php ArrayAccess::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns the value at the specified offset.
     *
     * @param mixed $offset The offset for which a value should be returned.
     *
     * @return mixed|null the value stored at the offset, or null if the offset
     *     does not exist.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Sets the given value to the given offset in the array.
     *
     * @param mixed|null $offset The offset to set. If `null`, the value may be
     *     set at a numerically-indexed offset.
     * @param mixed $value The value to set at the given offset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Removes the given offset and its value from the array.
     *
     * @param mixed $offset The offset to remove from the array.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * Returns a serialized string representation of this array object.
     *
     * @return string a PHP serialized string.
     *
     * @link http://php.net/manual/en/serializable.serialize.php Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize($this->data);
    }

    /**
     * Converts a serialized string representation into an instance object.
     *
     * @param string $serialized A PHP serialized string to unserialize.
     *
     * @link http://php.net/manual/en/serializable.unserialize.php Serializable::unserialize()
     */
    public function unserialize($serialized): void
    {
        $this->data = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Returns the number of items in this array.
     *
     * @return int
     *
     * @link http://php.net/manual/en/countable.count.php Countable::count()
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Removes all items from this array.
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * Returns a native PHP array representation of this array object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Returns `true` if this array is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }
}
