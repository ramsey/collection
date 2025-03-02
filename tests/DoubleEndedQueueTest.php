<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Mockery;
use Mockery\MockInterface;
use Ramsey\Collection\DoubleEndedQueue;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;
use stdClass;

/**
 * @covers \Ramsey\Collection\DoubleEndedQueue
 */
class DoubleEndedQueueTest extends TestCase
{
    public function testConstructorSetsType(): void
    {
        /** @var DoubleEndedQueue<int> $queue */
        $queue = new DoubleEndedQueue('integer');

        $this->assertSame('integer', $queue->getType());
    }

    public function testConstructorWithData(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['Foo', 'Bar']);

        $this->assertCount(2, $queue);
    }

    public function testOffsetSet(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');
        $queue[] = $this->faker->text();

        $this->assertCount(1, $queue);
    }

    public function testOffsetSetThrowsException(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is 42');

        /**
         * @phpstan-ignore-next-line
         */
        $queue[] = 42;
    }

    public function testValuesCanBeAdded(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $this->assertTrue($queue->add('Foo'));
        $this->assertCount(1, $queue);
    }

    public function testAddMayAddSameObjectMultipleTimes(): void
    {
        $expectedCount = 4;

        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        /** @var DoubleEndedQueue<stdClass> $queue1 */
        $queue1 = new DoubleEndedQueue(stdClass::class);

        /** @var DoubleEndedQueue<stdClass> $queue2 */
        $queue2 = new DoubleEndedQueue(stdClass::class);

        // Add the same object multiple times
        for ($i = 0; $i < $expectedCount; $i++) {
            $queue1[] = $obj1;
        }

        // Test the add() method
        for ($i = 0; $i < $expectedCount; $i++) {
            $queue2->add($obj1);
        }

        $this->assertCount($expectedCount, $queue1);
        $this->assertCount($expectedCount, $queue2);
    }

    public function testOfferAddsElement(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);

        $object = new stdClass();
        $object->name = $this->faker->name();

        $queue->offer($object);

        $this->assertCount(1, $queue);
        $this->assertSame($object, $queue->poll());
    }

    public function testIterateOverQueue(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);

        for ($i = 0; $i < 4; $i++) {
            $object = new stdClass();
            $object->id = $i;
            $queue->add($object);
        }

        $id = 0;
        foreach ($queue as $item) {
            $this->assertSame($id, $item->id);
            $id++;
        }
    }

    public function testElementDontRemovePeekFromQueue(): void
    {
        $object1 = new stdClass();
        $object1->name = 'foo';

        $object2 = new stdClass();
        $object2->name = 'bar';

        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->element());
        $this->assertSame($object1, $queue->element());
        $this->assertCount(2, $queue);
    }

    public function testElementThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->element();
    }

    public function testPeekReturnsObjects(): void
    {
        $object1 = new stdClass();
        $object1->name = $this->faker->name();

        $object2 = new stdClass();
        $object2->name = $this->faker->name();

        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->peek());
        $this->assertSame($object1, $queue->peek());
    }

    public function testPeekReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<bool> $queue */
        $queue = new DoubleEndedQueue('bool');

        $this->assertNull($queue->peek());
    }

    public function testPollRemovesTheHead(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $queue->add('Foo');
        $queue->add('Bar');

        $this->assertCount(2, $queue);
        $this->assertSame('Foo', $queue->poll());
        $this->assertCount(1, $queue);
        $this->assertSame('Bar', $queue->poll());
        $this->assertCount(0, $queue);
    }

    public function testPollReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);

        $this->assertNull($queue->poll());
    }

    public function testRemove(): void
    {
        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);

        // Add the same object multiple times
        $queue->add($obj1);
        $queue->add($obj1);
        $queue->add($obj1);

        $this->assertCount(3, $queue);
        $this->assertSame($obj1, $queue->remove());
        $this->assertCount(2, $queue);
    }

    public function testRemoveThrowsExceptionIfEmpty(): void
    {
        $object1 = new stdClass();
        $object1->name = $this->faker->name();

        $object2 = new stdClass();
        $object2->name = $this->faker->name();

        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->remove());
        $this->assertSame($object2, $queue->remove());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->remove();
    }

    public function testMixedUsageOfAllMethods(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $queue->add('Foo');
        $queue->add('Bar');

        $this->assertSame('Foo', $queue->peek());
        $this->assertSame('Foo', $queue->remove());

        $queue->add('Foo');

        $this->assertSame('Bar', $queue->peek());
        $this->assertSame('Bar', $queue->poll());

        $queue->offer('FooBar');

        $this->assertSame('Foo', $queue->remove());

        $this->assertCount(1, $queue);
    }

    public function testValuesCanBeAddedToTheHead(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['Baz']);

        $this->assertTrue($queue->addFirst('Bar'));
        $this->assertTrue($queue->addFirst('Foo'));
        $this->assertCount(3, $queue);
        $this->assertSame('Foo', $queue->firstElement());
        $this->assertSame('Baz', $queue->lastElement());
        $this->assertSame(['Foo', 'Bar', 'Baz'], $queue->toArray());
    }

    public function testAddFirstThrowsExceptionForIncorrectTypes(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is 42');

        /**
         * @phpstan-ignore-next-line
         */
        $queue->addFirst(42);
    }

    public function testValuesCanBeAddedToTheTail(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['Bar']);

        $this->assertTrue($queue->addLast('Foo'));
        $this->assertCount(2, $queue);
        $this->assertSame('Bar', $queue->firstElement());
        $this->assertSame('Foo', $queue->lastElement());
    }

    public function testFirstElementDontRemoveFromQueue(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame('foo', $queue->firstElement());
        $this->assertCount(2, $queue);
    }

    public function testLastElementDontRemoveFromQueue(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->lastElement());
        $this->assertSame('bar', $queue->lastElement());
        $this->assertCount(2, $queue);
    }

    public function testFirstElementThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->firstElement();
    }

    public function testLastElementThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->lastElement();
    }

    public function testPeekFirstReturnsObjects(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->peekFirst());
        $this->assertSame('foo', $queue->peekFirst());
    }

    public function testPeekLastReturnsObjects(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->peekLast());
        $this->assertSame('bar', $queue->peekLast());
    }

    public function testPeekFirstReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<bool> $queue */
        $queue = new DoubleEndedQueue('bool');

        $this->assertNull($queue->peekFirst());
    }

    public function testPeekLastReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<bool> $queue */
        $queue = new DoubleEndedQueue('bool');

        $this->assertNull($queue->peekLast());
    }

    public function testPollFirstRemovesTheHead(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertCount(2, $queue);
        $this->assertSame('foo', $queue->pollFirst());
        $this->assertCount(1, $queue);
        $this->assertSame('bar', $queue->pollFirst());
        $this->assertCount(0, $queue);
    }

    public function testPollLastRemovesTheTail(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertCount(2, $queue);
        $this->assertSame('bar', $queue->pollLast());
        $this->assertCount(1, $queue);
        $this->assertSame('foo', $queue->pollLast());
        $this->assertCount(0, $queue);
    }

    public function testPollFirstReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);

        $this->assertNull($queue->pollFirst());
    }

    public function testPollLastReturnsNullIfEmpty(): void
    {
        /** @var DoubleEndedQueue<stdClass> $queue */
        $queue = new DoubleEndedQueue(stdClass::class);

        $this->assertNull($queue->pollLast());
    }

    public function testRemoveFirst(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar', 'biz']);

        $this->assertCount(3, $queue);
        $this->assertSame('foo', $queue->removeFirst());
        $this->assertCount(2, $queue);
        $this->assertSame('bar', $queue->firstElement());
        $this->assertSame('biz', $queue->lastElement());
    }

    public function testRemoveLast(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar', 'biz']);

        $this->assertCount(3, $queue);
        $this->assertSame('biz', $queue->removeLast());
        $this->assertCount(2, $queue);
        $this->assertSame('foo', $queue->firstElement());
        $this->assertSame('bar', $queue->lastElement());
    }

    public function testRemoveFirstThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertSame('foo', $queue->removeFirst());
        $this->assertSame('bar', $queue->removeFirst());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->removeFirst();
    }

    public function testRemoveLastThrowsExceptionIfEmpty(): void
    {
        /** @var DoubleEndedQueue<string> $queue */
        $queue = new DoubleEndedQueue('string', ['foo', 'bar']);

        $this->assertSame('bar', $queue->removeLast());
        $this->assertSame('foo', $queue->removeLast());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->removeLast();
    }

    public function testMixedUsageOfAllQueueAndDequeueMethods(): void
    {
        /** @var DoubleEndedQueue<string> $deque */
        $deque = new DoubleEndedQueue('string');

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

        /** @var DoubleEndedQueue<string> & MockInterface $deque */
        $deque = Mockery::mock(DoubleEndedQueue::class);
        $deque->shouldReceive('offerFirst')->passthru();

        $deque->expects('addFirst')->with($element)->andThrow(InvalidArgumentException::class);

        $this->assertFalse($deque->offerFirst($element));
    }
}
