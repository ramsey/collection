<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Collection;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\InvalidPropertyOrMethod;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\Exception\UnsupportedOperationException;
use Ramsey\Collection\Test\Mock\Bar;
use Ramsey\Collection\Test\Mock\BarCollection;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\Mock\FooCollection;
use stdClass;

use function serialize;
use function unserialize;

/**
 * Tests for Collection, as well as coverage for AbstractCollection
 */
class CollectionTest extends TestCase
{
    public function testConstructorSetsType(): void
    {
        $collection = new Collection('string');

        $this->assertSame('string', $collection->getType());
    }

    public function testConstructorWithData(): void
    {
        $collection = new Collection('string', ['foo', 'bar']);

        $this->assertCount(2, $collection);
    }

    public function testOffsetSet(): void
    {
        /** @var Collection<int> $collection */
        $collection = new Collection('integer');
        $collection[] = $this->faker->numberBetween();

        // Ensure that an exception is thrown when attempting to add
        // an invalid type for this collection
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type integer');

        /**
         * @phpstan-ignore-next-line
         */
        $collection[] = $this->faker->text();
    }

    public function testOffsetSetPosition(): void
    {
        $offset = $this->faker->numberBetween(0, 100);
        $value = $this->faker->numberBetween(0, 100);

        /** @var Collection<int> $collection */
        $collection = new Collection('int');
        $collection->offsetSet($offset, $value);

        $this->assertSame($value, $collection->offsetGet($offset));
    }

    public function testAdd(): void
    {
        /** @var Collection<int> $collection */
        $collection = new Collection('integer');

        $this->assertTrue($collection->add($this->faker->numberBetween()));
    }

    public function testAddMayAddSameObjectMultipleTimes(): void
    {
        $expectedCount = 4;

        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        /** @var Collection<stdClass> $collection1 */
        $collection1 = new Collection('stdClass');

        /** @var Collection<stdClass> $collection2 */
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

    public function testContains(): void
    {
        $name = $this->faker->name();

        $obj1 = new stdClass();
        $obj1->name = $name;

        // Object with same properties but different identity
        $obj2 = new stdClass();
        $obj2->name = $name;

        /** @var Collection<stdClass> $collection */
        $collection = new Collection('stdClass');
        $collection->add($obj1);

        $this->assertTrue($collection->contains($obj1));
        $this->assertFalse($collection->contains($obj2));
    }

    public function testContainsNonStrict(): void
    {
        $name = $this->faker->name();

        $obj1 = new stdClass();
        $obj1->name = $name;

        // Object with same properties but different identity
        $obj2 = new stdClass();
        $obj2->name = $name;

        /** @var Collection<stdClass> $collection */
        $collection = new Collection('stdClass');
        $collection->add($obj1);

        $this->assertTrue($collection->contains($obj1, false));
        $this->assertTrue($collection->contains($obj2, false));
    }

    public function testRemove(): void
    {
        $obj1 = new stdClass();
        $obj1->name = $this->faker->name();

        /** @var Collection<stdClass> $collection */
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

    public function testSubclassBehavior(): void
    {
        $fooCollection = new FooCollection();

        $fooCollection[] = new Foo();
        $fooCollection[] = new Foo();
        $fooCollection[] = new Foo();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type ' . Foo::class);

        /**
         * @phpstan-ignore-next-line
         */
        $fooCollection[] = new stdClass();
    }

    public function testColumnByProperty(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertSame(['a', 'b', 'c'], $barCollection->column('name'));
    }

    public function testColumnByMethod(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertSame([1, 2, 3], $barCollection->column('getId'));
    }

    public function testColumnByArrayKey(): void
    {
        /** @var Collection<array{id: int, name: string}> $collection */
        $collection = new Collection('array', [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
            ['id' => 3, 'name' => 'c'],
        ]);

        $this->assertSame([1, 2, 3], $collection->column('id'));
        $this->assertSame(['a', 'b', 'c'], $collection->column('name'));
    }

    public function testColumnShouldRaiseExceptionOnUndefinedPropertyOrMethod(): void
    {
        $bar1 = new Bar(1, 'a');
        $barCollection = new BarCollection([$bar1]);

        $this->expectException(InvalidPropertyOrMethod::class);
        $this->expectExceptionMessage('Method or property "fu" not defined in Ramsey\Collection\Test\Mock\Bar');

        $barCollection->column('fu');
    }

    public function testColumnShouldRaiseExceptionWhenNotSupported(): void
    {
        /** @var Collection<int> $collection */
        $collection = new Collection('int', [1, 2, 3, 4]);

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('The collection type "int" does not support the $propertyOrMethod parameter');

        $collection->column('foo');
    }

    public function testFirstShouldRaiseExceptionOnEmptyCollection(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t determine first item. Collection is empty');
        $barCollection->first();
    }

    public function testFirst(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertSame($bar1, $barCollection->first());
        // Make sure the collection stays unchanged
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testLastShouldRaiseExceptionOnEmptyCollection(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Can\'t determine last item. Collection is empty');
        $barCollection->last();
    }

    public function testLast(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertSame($bar3, $barCollection->last());
        // Make sure the collection stays unchanged
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSerializable(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $collectionSerialized = serialize($barCollection);
        $barCollection2 = unserialize($collectionSerialized);

        $this->assertInstanceOf(BarCollection::class, $barCollection2);
        $this->assertContainsOnlyInstancesOf(Bar::class, $barCollection2);
        $this->assertEquals($barCollection, $barCollection2);
    }
}
