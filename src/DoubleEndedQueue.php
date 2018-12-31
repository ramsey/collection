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

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;

/**
 * This class provides a basic implementation of `DoubleEndedQueueInterface`, to
 * minimize the effort required to implement this interface.
 */
class DoubleEndedQueue extends Queue implements DoubleEndedQueueInterface
{
    /**
     * Index of the last element in the queue.
     *
     * @var int
     */
    private $tail = -1;

    /**
     * Sets the given value to the given offset in the queue.
     *
     * Since arbitrary offsets may not be manipulated in a queue, this method
     * serves only to fulfill the `ArrayAccess` interface requirements. It is
     * invoked by other operations when adding values to the queue.
     *
     * @param mixed|null $offset The offset is ignored and is treated as `null`.
     * @param mixed $value The value to set at the given offset.
     *
     * @throws InvalidArgumentException when the value does not match the
     *     specified type for this queue.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value): void
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . $this->toolValueToString($value)
            );
        }

        $this->tail++;

        $this->data[$this->tail] = $value;
    }

    /**
     * Ensures that the specified element is inserted at the front of this queue.
     *
     * @param mixed $element The element to add to this queue.
     *
     * @return bool `true` if this queue changed as a result of the call.
     *
     * @throws InvalidArgumentException when the value does not match the
     *     specified type for this queue.
     *
     * @see self::offerFirst()
     */
    public function addFirst($element): bool
    {
        if ($this->checkType($this->getType(), $element) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . $this->toolValueToString($element)
            );
        }

        $this->index--;

        $this->data[$this->index] = $element;

        return true;
    }

    /**
     * Ensures that the specified element in inserted at the end of this queue.
     *
     * @param mixed $element The element to add to this queue.
     *
     * @return bool `true` if this queue changed as a result of the call.
     *
     * @throws InvalidArgumentException when the value does not match the
     *     specified type for this queue.
     *
     * @see Queue::add()
     */
    public function addLast($element): bool
    {
        return $this->add($element);
    }

    /**
     * Inserts the specified element at the front this queue.
     *
     * @param mixed $element The element to add to this queue.
     *
     * @return bool `true` if the element was added to this queue, else `false`.
     *
     * @see self::addFirst()
     */
    public function offerFirst($element): bool
    {
        try {
            return $this->addFirst($element);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Inserts the specified element at the end this queue.
     *
     * @param mixed $element The element to add to this queue.
     *
     * @return bool `true` if the element was added to this queue, else `false`.
     *
     * @see self::addLast()
     * @see Queue::offer()
     */
    public function offerLast($element): bool
    {
        return $this->offer($element);
    }

    /**
     * Retrieves and removes the head of this queue.
     *
     * This method differs from `pollFirst()` only in that it throws an
     * exception if this queue is empty.
     *
     * @return mixed the head of this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     *
     * @see self::pollFirst()
     * @see Queue::remove()
     */
    public function removeFirst()
    {
        return $this->remove();
    }

    /**
     * Retrieves and removes the tail of this queue.
     *
     * This method differs from `pollLast()` only in that it throws an exception
     * if this queue is empty.
     *
     * @return mixed the tail of this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     *
     * @see self::pollLast()
     */
    public function removeLast()
    {
        if ($this->count() === 0) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }

        $tail = $this[$this->tail];

        unset($this[$this->tail]);
        $this->tail--;

        return $tail;
    }

    /**
     * Retrieves and removes the head of this queue, or returns `null` if this
     * queue is empty.
     *
     * @return mixed|null the head of this queue, or `null` if this queue is empty.
     *
     * @see self::removeFirst()
     */
    public function pollFirst()
    {
        return $this->poll();
    }

    /**
     * Retrieves and removes the tail of this queue, or returns `null` if this
     * queue is empty.
     *
     * @return mixed|null the tail of this queue, or `null` if this queue is empty.
     *
     * @see self::removeLast()
     */
    public function pollLast()
    {
        if ($this->count() === 0) {
            return null;
        }

        $tail = $this[$this->tail];

        unset($this[$this->tail]);
        $this->tail--;

        return $tail;
    }

    /**
     * Retrieves, but does not remove, the head of this queue.
     *
     * This method differs from `peekFirst()` only in that it throws an
     * exception if this queue is empty.
     *
     * @return mixed the head of this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     *
     * @see self::peekFirst()
     * @see Queue::element()
     */
    public function firstElement()
    {
        return $this->element();
    }

    /**
     * Retrieves, but does not remove, the tail of this queue.
     *
     * This method differs from `peekLast()` only in that it throws an exception
     * if this queue is empty.
     *
     * @return mixed the tail of this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     *
     * @see self::peekLast()
     */
    public function lastElement()
    {
        if ($this->count() === 0) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }

        return $this->data[$this->tail];
    }

    /**
     * Retrieves, but does not remove, the head of this queue, or returns `null`
     * if this queue is empty.
     *
     * @return mixed|null the head of this queue, or `null` if this queue is empty.
     *
     * @see self::firstElement()
     * @see Queue::peek()
     */
    public function peekFirst()
    {
        return $this->peek();
    }

    /**
     * Retrieves, but does not remove, the tail of this queue, or returns `null`
     * if this queue is empty.
     *
     * @return mixed|null the tail of this queue, or `null` if this queue is empty
     *
     * @see self::lastElement()
     */
    public function peekLast()
    {
        if ($this->count() === 0) {
            return null;
        }

        return $this->data[$this->tail];
    }
}
