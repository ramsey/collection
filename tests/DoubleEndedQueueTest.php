<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Mockery;
use Ramsey\Collection\DoubleEndedQueue;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\QueueInterface;
use stdClass;

/**
 * @covers \Ramsey\Collection\DoubleEndedQueue
 */
class DoubleEndedQueueTest extends TestCase
{
    use QueueBehavior;

    /**
     * @param mixed[] $data
     *
     * @return DoubleEndedQueue<T>
     *
     * @template T
     */
    protected function queue(string $type, array $data = []): QueueInterface
    {
        return new DoubleEndedQueue($type, $data);
    }

    public function testValuesCanBeAddedToTheHead(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['Bar']);

        $this->assertTrue($queue->addFirst('Foo'));
        $this->assertCount(2, $queue);
        $this->assertSame('Foo', $queue->firstElement());
        $this->assertSame('Bar', $queue->lastElement());
    }

    public function testAddFirstThrowsExceptionForIncorrectTypes(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is 42');

        // @phpstan-ignore-next-line
        $queue->addFirst(42);
    }

    public function testValuesCanBeAddedToTheTail(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['Bar']);

        $this->assertTrue($queue->addLast('Foo'));
        $this->assertCount(2, $queue);
        $this->assertSame('Bar', $queue->firstElement());
        $this->assertSame('Foo', $queue->lastElement());
    }

    public function testFirstElementDontRemoveFromQueue(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame('foo', $queue->firstElement());
        $this->assertCount(2, $queue);
    }

    public function testLastElementDontRemoveFromQueue(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->lastElement());
        $this->assertSame('bar', $queue->lastElement());
        $this->assertCount(2, $queue);
    }

    public function testFirstElementThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->firstElement();
    }

    public function testLastElementThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->lastElement();
    }

    public function testPeekFirstReturnsObjects(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->peekFirst());
        $this->assertSame('foo', $queue->peekFirst());
    }

    public function testPeekLastReturnsObjects(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->peekLast());
        $this->assertSame('bar', $queue->peekLast());
    }

    public function testPeekFirstReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<bool> $queue */
        $queue = $this->queue('bool');

        $this->assertNull($queue->peekFirst());
    }

    public function testPeekLastReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<bool> $queue */
        $queue = $this->queue('bool');

        $this->assertNull($queue->peekLast());
    }

    public function testPollFirstRemovesTheHead(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertCount(2, $queue);
        $this->assertSame('foo', $queue->pollFirst());
        $this->assertCount(1, $queue);
        $this->assertSame('bar', $queue->pollFirst());
        $this->assertCount(0, $queue);
    }

    public function testPollLastRemovesTheTail(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertCount(2, $queue);
        $this->assertSame('bar', $queue->pollLast());
        $this->assertCount(1, $queue);
        $this->assertSame('foo', $queue->pollLast());
        $this->assertCount(0, $queue);
    }

    public function testPollFirstReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = $this->queue(stdClass::class);

        $this->assertNull($queue->pollFirst());
    }

    public function testPollLastReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = $this->queue(stdClass::class);

        $this->assertNull($queue->pollLast());
    }

    public function testRemoveFirst(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar', 'biz']);

        $this->assertCount(3, $queue);
        $this->assertSame('foo', $queue->removeFirst());
        $this->assertCount(2, $queue);
        $this->assertSame('bar', $queue->firstElement());
        $this->assertSame('biz', $queue->lastElement());
    }

    public function testRemoveLast(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar', 'biz']);

        $this->assertCount(3, $queue);
        $this->assertSame('biz', $queue->removeLast());
        $this->assertCount(2, $queue);
        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame('bar', $queue->lastElement());
    }

    public function testRemoveFirstThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->removeFirst());
        $this->assertSame('bar', $queue->removeFirst());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->removeFirst();
    }

    public function testRemoveLastThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = $this->queue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->removeLast());
        $this->assertSame('foo', $queue->removeLast());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->removeLast();
    }

    public function testMixedUsageOfAllQueueAndDequeueMethods(): void
    {
        /** @var DoubleEndedQueue<string> $deque */
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

        $this->assertCount(2, $deque);
        $this->assertSame('bar', $deque->firstElement());
        $this->assertSame('yop', $deque->lastElement());
    }

    public function testOfferFirstReturnsFalseOnException(): void
    {
        $element = 'foo';

        $deque = Mockery::mock(DoubleEndedQueue::class);
        $deque->shouldReceive('offerFirst')->passthru();

        $deque->expects()->addFirst($element)->andThrow(InvalidArgumentException::class);

        $this->assertFalse($deque->offerFirst($element));
    }
}
