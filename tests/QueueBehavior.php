<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\QueueInterface;
use stdClass;

trait QueueBehavior
{
    /**
     * @param mixed[] $data
     */
    abstract protected function queue(string $type, array $data = []): QueueInterface;

    public function testConstructorSetsType(): void
    {
        $queue = $this->queue('integer');

        $this->assertEquals('integer', $queue->getType());
    }

    public function testConstructorWithData(): void
    {
        $queue = $this->queue('string', ['Foo', 'Bar']);

        $this->assertEquals(2, $queue->count());
    }

    public function testOffsetSet(): void
    {
        $queue = $this->queue('string');
        $queue[] = $this->faker->text();

        $this->assertSame(1, $queue->count());
    }

    public function testOffsetSetThrowsException(): void
    {
        $queue = $this->queue('string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is 42');
        $queue[] = 42;
    }

    public function testValuesCanBeAdded(): void
    {
        $queue = $this->queue('string');

        $this->assertTrue($queue->add('Foo'));
        $this->assertSame(1, $queue->count());
    }

    public function testAddMayAddSameObjectMultipleTimes(): void
    {
        $expectedCount = 4;

        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        $queue1 = $this->queue(stdClass::class);
        $queue2 = $this->queue(stdClass::class);

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
        $queue = $this->queue(stdClass::class);

        $object = new stdClass();
        $object->name = $this->faker->name();

        $queue->offer($object);

        $this->assertSame(1, $queue->count());
        $this->assertSame($object, $queue->poll());
    }

    public function testIterateOverQueue(): void
    {
        $queue = $this->queue(stdClass::class);

        for ($i = 0; $i < 4; $i++) {
            $object = new stdClass();
            $object->id = $i;
            $queue->add($object);
        }

        $id = 0;
        foreach ($queue as $item) {
            $this->assertEquals($id, $item->id);
            $id++;
        }
    }

    public function testElementDontRemovePeekFromQueue(): void
    {
        $object1 = new stdClass();
        $object1->name = 'foo';

        $object2 = new stdClass();
        $object2->name = 'bar';

        $queue = $this->queue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->element());
        $this->assertSame($object1, $queue->element());
        $this->assertSame(2, $queue->count());
    }

    public function testElementThrowsExceptionIfEmpty(): void
    {
        $queue = $this->queue('string');

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

        $queue = $this->queue(stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->peek());
        $this->assertSame($object1, $queue->peek());
    }

    public function testPeekReturnsNullIfEmpty(): void
    {
        $queue = $this->queue('bool');

        $this->assertNull($queue->peek());
    }

    public function testPollRemovesTheHead(): void
    {
        $queue = $this->queue('string');

        $queue->add('Foo');
        $queue->add('Bar');

        $this->assertSame(2, $queue->count());
        $this->assertSame('Foo', $queue->poll());
        $this->assertSame(1, $queue->count());
        $this->assertSame('Bar', $queue->poll());
        $this->assertSame(0, $queue->count());
    }

    public function testPollReturnsNullIfEmpty(): void
    {
        $queue = $this->queue(stdClass::class);

        $this->assertNull($queue->poll());
    }

    public function testRemove(): void
    {
        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        $queue = $this->queue(stdClass::class);

        // Add the same object multiple times
        $queue->add($obj1);
        $queue->add($obj1);
        $queue->add($obj1);

        $this->assertSame(3, $queue->count());
        $this->assertSame($obj1, $queue->remove());
        $this->assertSame(2, $queue->count());
    }

    public function testRemoveThrowsExceptionIfEmpty(): void
    {
        $object1 = new stdClass();
        $object1->name = $this->faker->name();

        $object2 = new stdClass();
        $object2->name = $this->faker->name();

        $queue = $this->queue(stdClass::class);
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
        $queue = $this->queue('string');

        $queue->add('Foo');
        $queue->add('Bar');

        $this->assertSame('Foo', $queue->peek());
        $this->assertSame('Foo', $queue->remove());

        $queue->add('Foo');

        $this->assertSame('Bar', $queue->peek());
        $this->assertSame('Bar', $queue->poll());

        $queue->offer('FooBar');

        $this->assertSame('Foo', $queue->remove());

        $this->assertSame(1, $queue->count());
    }
}
