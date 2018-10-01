<?php
declare(strict_types=1);

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

interface DoubleEndedQueueInterface extends QueueInterface
{
    /**
     * Ensures that the specified element is inserted at the front of this queue.
     * Returns true if this queue changed as a result of the call. (Returns
     * false if this queue does not permit duplicates and already contains the
     * specified element.)
     *
     * Queues that support this operation may place limitations on what
     * elements may be added to this queue. In particular, some
     * queues will refuse to add null elements, and others will impose
     * restrictions on the type of elements that may be added. Queue
     * classes should clearly specify in their documentation any restrictions
     * on what elements may be added.
     *
     * If a queue refuses to add a particular element for any reason other
     * than that it already contains the element, it must throw an exception
     * (rather than returning false). This preserves the invariant that a
     * queue always contains the specified element after this call returns.
     *
     * @param mixed $element
     * @return bool true if this queue changed as a result of the call
     */
    public function addFirst($element): bool;

    /**
     * Ensures that the specified element in inserted at the end of this queue.
     * Returns true if this queue changed as a result of the call. (Returns
     * false if this queue does not permit duplicates and already contains the
     * specified element.)
     *
     * Queues that support this operation may place limitations on what
     * elements may be added to this queue. In particular, some
     * queues will refuse to add null elements, and others will impose
     * restrictions on the type of elements that may be added. Queue
     * classes should clearly specify in their documentation any restrictions
     * on what elements may be added.
     *
     * If a queue refuses to add a particular element for any reason other
     * than that it already contains the element, it must throw an exception
     * (rather than returning false). This preserves the invariant that a
     * queue always contains the specified element after this call returns.
     *
     * @param mixed $element
     * @return bool true if this queue changed as a result of the call
     */
    public function addLast($element): bool;

    /**
     * Inserts the specified element at the front this queue if it is possible
     * to do so immediately without violating capacity restrictions. When using
     * a capacity-restricted queue, this method is generally preferable to
     * add(E), which can fail to insert an element only by throwing an exception.
     *
     * @param $element
     * @return mixed true if the element was added to this queue, else false
     */
    public function offerFirst($element);

    /**
     * Inserts the specified element at the end this queue if it is possible
     * to do so immediately without violating capacity restrictions. When using
     * a capacity-restricted queue, this method is generally preferable to add(E),
     * which can fail to insert an element only by throwing an exception.
     *
     * @param $element
     * @return mixed true if the element was added to this queue, else false
     */
    public function offerLast($element);

    /**
     * Retrieves and removes the head of this queue. This method differs
     * from pollFirst only in that it throws an exception if this queue is empty.
     *
     * @return mixed the head of this queue
     * @throws \Ramsey\Collection\Exception\NoSuchElementException
     */
    public function removeFirst();

    /**
     * Retrieves and removes the tail of this queue. This method differs
     * from pollLast only in that it throws an exception if this queue is empty.
     *
     * @return mixed the tail of this queue
     * @throws \Ramsey\Collection\Exception\NoSuchElementException
     */
    public function removeLast();

    /**
     * Retrieves and removes the head of this queue, or returns null
     * if this queue is empty.
     *
     * @return mixed the head of this queue, or null if this queue is empty
     */
    public function pollFirst();

    /**
     * Retrieves and removes the tail of this queue, or returns null
     * if this queue is empty.
     *
     * @return mixed the tail of this queue, or null if this queue is empty
     */
    public function pollLast();

    /**
     * Retrieves, but does not remove, the head of this queue. This method
     * differs from peekFirst only in that it throws an exception if this queue
     * is empty.
     *
     * @return mixed the head of this queue
     * @throws \Ramsey\Collection\Exception\NoSuchElementException
     */
    public function firstElement();

    /**
     * Retrieves, but does not remove, the tail of this queue. This method
     * differs from peekLast only in that it throws an exception if this queue
     * is empty.
     *
     * @return mixed the tail of this queue
     * @throws \Ramsey\Collection\Exception\NoSuchElementException
     */
    public function lastElement();

    /**
     * Retrieves, but does not remove, the head of this queue, or returns null
     * if this queue is empty.
     *
     * @return mixed the head of this queue, or null if this queue is empty
     */
    public function peekFirst();

    /**
     * Retrieves, but does not remove, the tail of this queue, or returns null
     * if this queue is empty.
     *
     * @return mixed the tail of this queue, or null if this queue is empty
     */
    public function peekLast();
}
