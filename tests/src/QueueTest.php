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

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\Queue;

/**
 * @package Ramsey\Collection\Test
 * @covers \Ramsey\Collection\Queue
 */
class QueueTest extends TestCase
{

    public function testConstructorSetsType()
    {
        $queue = new Queue('integer');
        
        $this->assertEquals('integer', $queue->getType());
    }

    public function testConstructorWithData()
    {
        $queue = new Queue('string', ['Foo', 'Bar']);

        $this->assertEquals(2, $queue->count());
    }

    public function testOffsetSet()
    {
        $queue = new Queue('string');
        $queue[] = $this->faker->text();

        $this->assertSame(1, $queue->count());
    }

    public function testOffsetSetThrowsException()
    {
        $queue = new Queue('string');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectException('Unable to add a integer to the queue. Only strings allowed.');
        $queue[] = $this->faker->numberBetween();
    }

    public function testValuesCanAdded()
    {
        $queue = new Queue('string');
        
        $this->assertTrue($queue->add('Foo'));
    }

    public function testAddMayAddSameObjectMultipleTimes()
    {
        $expectedCount = 4;

        $obj1 = new \stdClass();
        $obj1->name = $this->faker->name();

        $queue1 = new Queue(\stdClass::class);
        $queue2 = new Queue(\stdClass::class);

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

    public function testIterateOverQueue()
    {
        $queue = new Queue(\stdClass::class);

        for ($i = 0; $i < 4; $i++) {
            $object = new \stdClass();
            $object->id = $i;
            $queue->add($object);
        }

        $id = 0;
        foreach ($queue as $item) {
            $this->assertEquals($id, $item->id);
            $id++;
        }
    }

    public function testElementDontRemovePeekFromQueue()
    {
        $object1 = new \stdClass();
        $object1->name = 'foo';

        $object2 = new \stdClass();
        $object2->name = 'bar';

        $queue = new Queue(\stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->element());
        $this->assertSame($object1, $queue->element());
        $this->assertSame(2, $queue->count());
    }

    public function testElementThrowsExceptionIfEmpty()
    {
        $queue = new Queue('string');

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->element();
    }

    public function testPeekReturnsObjects()
    {
        $object1 = new \stdClass();
        $object1->name = $this->faker->name();

        $object2 = new \stdClass();
        $object2->name = $this->faker->name();

        $queue = new Queue();
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->peek());
        $this->assertSame($object2, $queue->peek());
        $this->assertFalse($queue->peek());
    }

    public function testPollReturnsNullIfEmpty()
    {
        $object1 = new \stdClass();
        $object1->name = $this->faker->name();

        $object2 = new \stdClass();
        $object2->name = $this->faker->name();

        $queue = new Queue();
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->peek());
        $this->assertSame($object2, $queue->peek());
        $this->assertSame(null, $queue->peek());
    }

    public function testRemove()
    {
        $obj1 = new \stdClass();
        $obj1->name = $this->faker->name();

        $queue = new Queue(\stdClass::class);

        // Add the same object multiple times
        $queue->add($obj1);
        $queue->add($obj1);
        $queue->add($obj1);

        $this->assertSame(3, $queue->count());
        $this->assertTrue($queue->remove($obj1));
        $this->assertSame(2, $queue->count());
    }

    public function testRemoveThrowsExceptionIfEmpty()
    {
        $object1 = new \stdClass();
        $object1->name = $this->faker->name();

        $object2 = new \stdClass();
        $object2->name = $this->faker->name();

        $queue = new Queue(\stdClass::class);
        $queue->add($object1);
        $queue->add($object2);

        $this->assertSame($object1, $queue->remove());
        $this->assertSame($object2, $queue->remove());

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t return element from Queue. Queue is empty.');

        $queue->remove();
    }
}
