<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Mockery;
use Mockery\MockInterface;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\Queue;
use stdClass;

/**
 * @covers \Ramsey\Collection\Queue
 */
class QueueTest extends TestCase
{
    public function testConstructorSetsType(): void
    {
        /** @var Queue<int> $queue */
        $queue = new Queue('integer');

        $this->assertSame('integer', $queue->getType());
    }

    public function testConstructorWithData(): void
    {
        /** @var Queue<string> $queue */
        $queue = new Queue('string', ['Foo', 'Bar']);

        $this->assertCount(2, $queue);
    }

    public function testOffsetSet(): void
    {
        /** @var Queue<string> $queue */
        $queue = new Queue('string');
        $queue[] = $this->faker->text();

        $this->assertCount(1, $queue);
    }

    public function testOffsetSetThrowsException(): void
    {
        /** @var Queue<string> $queue */
        $queue = new Queue('string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is 42');

        /**
         * @phpstan-ignore-next-line
         */
        $queue[] = 42;
    }

    public function testValuesCanBeAdded(): void
    {
        /** @var Queue<string> $queue */
        $queue = new Queue('string');

        $this->assertTrue($queue->add('Foo'));
        $this->assertCount(1, $queue);
    }

    public function testAddMayAddSameObjectMultipleTimes(): void
    {
        $expectedCount = 4;

        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        /** @var Queue<stdClass> $queue1 */
        $queue1 = new Queue(stdClass::class);

        /** @var Queue<stdClass> $queue2 */
        $queue2 = new Queue(stdClass::class);

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
        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);

        $object = new stdClass();
        $object->name = $this->faker->name();

        $queue->offer($object);

        $this->assertCount(1, $queue);
        $this->assertSame($object, $queue->poll());
    }

    public function testIterateOverQueue(): void
    {
        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);

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

        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->element());
        $this->assertSame($object1, $queue->element());
        $this->assertCount(2, $queue);
    }

    public function testElementThrowsExceptionIfEmpty(): void
    {
        /** @var Queue<string> $queue */
        $queue = new Queue('string');

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

        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->peek());
        $this->assertSame($object1, $queue->peek());
    }

    public function testPeekReturnsNullIfEmpty(): void
    {
        /** @var Queue<bool> $queue */
        $queue = new Queue('bool');

        $this->assertNull($queue->peek());
    }

    public function testPollRemovesTheHead(): void
    {
        /** @var Queue<string> $queue */
        $queue = new Queue('string');

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
        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);

        $this->assertNull($queue->poll());
    }

    public function testRemove(): void
    {
        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);

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

        /** @var Queue<stdClass> $queue */
        $queue = new Queue(stdClass::class);
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
        /** @var Queue<string> $queue */
        $queue = new Queue('string');

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

    public function testOfferReturnsFalseOnException(): void
    {
        $element = 'foo';

        /** @var Queue<string> & MockInterface $queue */
        $queue = Mockery::mock(Queue::class);
        $queue->shouldReceive('offer')->passthru();

        $queue->expects('add')->with($element)->andThrow(InvalidArgumentException::class);

        $this->assertFalse($queue->offer($element));
    }
}
