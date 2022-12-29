<?php

/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Collection;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;

/**
 * This class provides a basic implementation of `DoubleEndedQueueInterface`, to
 * minimize the effort required to implement this interface.
 *
 * @template T
 * @extends Queue<T>
 * @implements DoubleEndedQueueInterface<T>
 */
class DoubleEndedQueue extends Queue implements DoubleEndedQueueInterface
{
    /**
     * Index of the last element in the queue.
     */
    private int $tail = -1;

    /**
     * @throws InvalidArgumentException if $value is of the wrong type
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . $this->toolValueToString($value),
            );
        }

        $this->tail++;

        $this->data[$this->tail] = $value;
    }

    /**
     * @throws InvalidArgumentException if $element is of the wrong type
     */
    public function addFirst(mixed $element): bool
    {
        if ($this->checkType($this->getType(), $element) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . $this->toolValueToString($element),
            );
        }

        $this->index--;

        $this->data[$this->index] = $element;

        return true;
    }

    /**
     * @throws InvalidArgumentException if $element is of the wrong type
     */
    public function addLast(mixed $element): bool
    {
        return $this->add($element);
    }

    public function offerFirst(mixed $element): bool
    {
        try {
            return $this->addFirst($element);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    public function offerLast(mixed $element): bool
    {
        return $this->offer($element);
    }

    /**
     * @return T the first element in this queue.
     *
     * @throws NoSuchElementException if the queue is empty
     */
    public function removeFirst(): mixed
    {
        return $this->remove();
    }

    /**
     * @return T the last element in this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     */
    public function removeLast(): mixed
    {
        $tail = $this->pollLast();

        if ($tail === null) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }

        return $tail;
    }

    /**
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    public function pollFirst(): mixed
    {
        return $this->poll();
    }

    /**
     * @return T | null the tail of this queue, or `null` if this queue is empty.
     */
    public function pollLast(): mixed
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
     * @return T the head of this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     */
    public function firstElement(): mixed
    {
        return $this->element();
    }

    /**
     * @return T the tail of this queue.
     *
     * @throws NoSuchElementException if this queue is empty.
     */
    public function lastElement(): mixed
    {
        if ($this->count() === 0) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }

        return $this->data[$this->tail];
    }

    /**
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    public function peekFirst(): mixed
    {
        return $this->peek();
    }

    /**
     * @return T | null the tail of this queue, or `null` if this queue is empty.
     */
    public function peekLast(): mixed
    {
        if ($this->count() === 0) {
            return null;
        }

        return $this->data[$this->tail];
    }
}
