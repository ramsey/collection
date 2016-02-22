<?php
namespace Ramsey\Collection\Test;

use Ramsey\Collection\Collection;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\Mock\FooCollection;
use Ramsey\Collection\Test\TestCase;

/**
 * Tests for Collection, as well as coverage for AbstractCollection
 */
class CollectionTest extends TestCase
{
    public function testContructorSetsType()
    {
        $collection = new Collection('string');

        $this->assertEquals('string', $collection->getType());
    }

    public function testOffsetSet()
    {
        $collection = new Collection('integer');
        $collection[] = $this->faker->numberBetween();

        // Ensure that an exception is thrown when attempting to add
        // an invalid type for this collection
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type integer');
        $collection[] = $this->faker->text();
    }

    public function testAdd()
    {
        $collection = new Collection('integer');

        $this->assertTrue($collection->add($this->faker->numberBetween()));
    }

    public function testAddMayAddSameObjectMultipleTimes()
    {
        $expectedCount = 4;

        $obj1 = new \stdClass();
        $obj1->name = $this->faker->name();

        $collection1 = new Collection('stdClass');
        $collection2 = new Collection('stdClass');

        // Add the same object multiple times
        for ($i = 0; $i < $expectedCount; $i++) {
            $collection1[] = $obj1;
        }

        // Test the add() method
        for ($i = 0; $i < $expectedCount; $i++) {
            $collection2->add($obj1);
        }

        $this->assertCount($expectedCount, $collection1);
        $this->assertCount($expectedCount, $collection2);
    }

    public function testContains()
    {
        $name = $this->faker->name();

        $obj1 = new \stdClass();
        $obj1->name = $name;

        // Object with same properties but different identity
        $obj2 = new \stdClass();
        $obj2->name = $name;

        $collection = new Collection('stdClass');
        $collection->add($obj1);

        $this->assertTrue($collection->contains($obj1));
        $this->assertFalse($collection->contains($obj2));
    }

    public function testContainsNonStrict()
    {
        $name = $this->faker->name();

        $obj1 = new \stdClass();
        $obj1->name = $name;

        // Object with same properties but different identity
        $obj2 = new \stdClass();
        $obj2->name = $name;

        $collection = new Collection('stdClass');
        $collection->add($obj1);

        $this->assertTrue($collection->contains($obj1, false));
        $this->assertTrue($collection->contains($obj2, false));
    }

    public function testRemove()
    {
        $obj1 = new \stdClass();
        $obj1->name = $this->faker->name();

        $collection = new Collection('stdClass');

        // Add the same object multiple times
        $collection->add($obj1);
        $collection->add($obj1);
        $collection->add($obj1);

        $this->assertTrue($collection->remove($obj1));
        $this->assertTrue($collection->remove($obj1));
        $this->assertTrue($collection->remove($obj1));
        $this->assertFalse($collection->remove($obj1));
    }

    public function testSubclassBehavior()
    {
        $fooCollection = new FooCollection();

        $fooCollection[] = new Foo();
        $fooCollection[] = new Foo();
        $fooCollection[] = new Foo();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type ' . Foo::class);
        $fooCollection[] = new \stdClass();
    }
}