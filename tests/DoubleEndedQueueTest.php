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

namespace Ramsey\Collection\Test;

use Ramsey\Collection\DoubleEndedQueue;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;

/**
 * @package Ramsey\Collection\Test
 * @covers \Ramsey\Collection\DoubleEndedQueue
 */
class DoubleEndedQueueTest extends TestCase
{
    use QueueBehavior;

    protected function queue(string $type, array $data = []): DoubleEndedQueue
    {
        return new DoubleEndedQueue($type, $data);
    }

    public function testValuesCanBeAddedToTheHead()
    {
        $queue = $this->queue('string', ['Bar']);

        $this->assertTrue($queue->addFirst('Foo'));
        $this->assertSame(2, $queue->count());
        $this->assertSame('Foo', $queue->firstElement());
        $this->assertSame('Bar', $queue->lastElement());
    }

    public function testAddFirstThrowsExceptionForIncorrectTypes()
    {
        $queue = $this->queue('string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is 42');
        $queue->addFirst(42);
    }

    public function testValuesCanBeAddedToTheTail()
    {
        $queue = $this->queue('string', ['Bar']);

        $this->assertTrue($queue->addLast('Foo'));
        $this->assertSame(2, $queue->count());
        $this->assertSame('Bar', $queue->firstElement());
        $this->assertSame('Foo', $queue->lastElement());
    }

    public function testFirstElementDontRemoveFromQueue()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame(2, $queue->count());
    }

    public function testLastElementDontRemoveFromQueue()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->lastElement());
        $this->assertSame('bar', $queue->lastElement());
        $this->assertSame(2, $queue->count());
    }

    public function testFirstElementThrowsExceptionIfEmpty()
    {
        $queue = $this->queue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->firstElement();
    }

    public function testLastElementThrowsExceptionIfEmpty()
    {
        $queue = $this->queue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->lastElement();
    }

    public function testPeekFirstReturnsObjects()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->peekFirst());
        $this->assertSame('foo', $queue->peekFirst());
    }

    public function testPeekLastReturnsObjects()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->peekLast());
        $this->assertSame('bar', $queue->peekLast());
    }

    public function testPeekFirstReturnsNullIfEmpty()
    {
        $queue = $this->queue('bool');

        $this->assertNull($queue->peekFirst());
    }

    public function testPeekLastReturnsNullIfEmpty()
    {
        $queue = $this->queue('bool');

        $this->assertNull($queue->peekLast());
    }

    public function testPollFirstRemovesTheHead()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame(2, $queue->count());
        $this->assertSame('foo', $queue->pollFirst());
        $this->assertSame(1, $queue->count());
        $this->assertSame('bar', $queue->pollFirst());
        $this->assertSame(0, $queue->count());
    }

    public function testPollLastRemovesTheTail()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame(2, $queue->count());
        $this->assertSame('bar', $queue->pollLast());
        $this->assertSame(1, $queue->count());
        $this->assertSame('foo', $queue->pollLast());
        $this->assertSame(0, $queue->count());
    }

    public function testPollFirstReturnsNullIfEmpty()
    {
        $queue = $this->queue(\stdClass::class);

        $this->assertSame(null, $queue->pollFirst());
    }

    public function testPollLastReturnsNullIfEmpty()
    {
        $queue = $this->queue(\stdClass::class);

        $this->assertSame(null, $queue->pollLast());
    }

    public function testRemoveFirst()
    {
        $queue = $this->queue('string', ['foo', 'bar', 'biz']);

        $this->assertSame(3, $queue->count());
        $this->assertSame('foo', $queue->removeFirst());
        $this->assertSame(2, $queue->count());
        $this->assertSame('bar', $queue->firstElement());
        $this->assertSame('biz', $queue->lastElement());
    }

    public function testRemoveLast()
    {
        $queue = $this->queue('string', ['foo', 'bar', 'biz']);

        $this->assertSame(3, $queue->count());
        $this->assertSame('biz', $queue->removeLast());
        $this->assertSame(2, $queue->count());
        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame('bar', $queue->lastElement());
    }

    public function testRemoveFirstThrowsExceptionIfEmpty()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->removeFirst());
        $this->assertSame('bar', $queue->removeFirst());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->removeFirst();
    }

    public function testRemoveLastThrowsExceptionIfEmpty()
    {
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->removeLast());
        $this->assertSame('foo', $queue->removeLast());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->removeLast();
    }

    public function testMixedUsageOfAllQueueAndDequeueMethods()
    {
        $deque = $this->queue('string');

        $deque->add('foo');
        $deque->add('bar');
        $deque->addLast('yop');
        $deque->addFirst('biz');

        // deque should contain: biz, foo, bar, yop

        $this->assertSame('biz', $deque->peek());
        $this->assertSame('biz', $deque->peekFirst());
        $this->assertSame('yop', $deque->peekLast());
        $this->assertSame('biz', $deque->remove());

        $deque->add('biz');

        // deque should contain: foo, bar, yop, biz

        $this->assertSame('foo', $deque->peek());
        $this->assertSame('foo', $deque->poll());
        $this->assertSame('biz', $deque->peekLast());
        $this->assertSame('biz', $deque->pollLast());

        $deque->offerLast('foobar');
        $deque->offerFirst('barfoo');

        // deque should contain: barfoo, bar, yop, foobar

        $this->assertSame('foobar', $deque->removeLast());
        $this->assertSame('barfoo', $deque->removeFirst());

        // deque should contain: bar, yop

        $this->assertSame(2, $deque->count());
        $this->assertSame('bar', $deque->firstElement());
        $this->assertSame('yop', $deque->lastElement());
    }
}
