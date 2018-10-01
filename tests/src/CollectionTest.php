<?php
declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Collection;
use Ramsey\Collection\Exception\DiverseCollectionException;
use Ramsey\Collection\Exception\InvalidSortOrderException;
use Ramsey\Collection\Exception\OutOfBoundsException;
use Ramsey\Collection\Exception\ValueExtractionException;
use Ramsey\Collection\Test\Mock\Bar;
use Ramsey\Collection\Test\Mock\BarCollection;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\Mock\FooCollection;

/**
 * Tests for Collection, as well as coverage for AbstractCollection
 */
class CollectionTest extends TestCase
{
    public function testConstructorSetsType()
    {
        $collection = new Collection('string');

        $this->assertEquals('string', $collection->getType());
    }

    public function testConstructorWithData()
    {
        $collection = new Collection('string', ['foo', 'bar']);

        $this->assertCount(2, $collection);
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

    public function testColumnByProperty()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertEquals(['a', 'b', 'c'], $barCollection->column('name'));
    }

    public function testColumnByMethod()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertEquals([1, 2, 3], $barCollection->column('getId'));
    }

    public function testColumnShouldRaiseExceptionOnUndefinedPropertyOrMethod()
    {
        $bar1 = new Bar(1, 'a');
        $barCollection = new BarCollection([$bar1]);

        $this->expectException(ValueExtractionException::class);
        $this->expectExceptionMessage('Method or property "fu" not defined in Ramsey\Collection\Test\Mock\Bar');
        $barCollection->column('fu');
    }

    public function testFirstShouldRaiseExceptionOnEmptyCollection()
    {
        $barCollection = new BarCollection();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Can\'t determine first item. Collection is empty');
        $barCollection->first();
    }

    public function testFirst()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertSame($bar1, $barCollection->first());
    }

    public function testLastShouldRaiseExceptionOnEmptyCollection()
    {
        $barCollection = new BarCollection();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Can\'t determine last item. Collection is empty');
        $barCollection->last();
    }

    public function testLast()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $this->assertSame($bar3, $barCollection->last());
    }

    public function testSortNameAscWithAscendingIdAndNames()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar3, $bar2, $bar1]);

        $sortedCollection = $barCollection->sort('name');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar3, $bar2, $bar1], $barCollection->toArray());
        $this->assertEquals([$bar1, $bar2, $bar3], $sortedCollection->toArray());
    }

    public function testSortNameAscWithDescendingNames()
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('name');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar1, $bar2, $bar3], $barCollection->toArray());
        $this->assertEquals([$bar3, $bar2, $bar1], $sortedCollection->toArray());
    }

    public function testSortNameDescWithDescendingNames()
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('name', 'desc');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar1, $bar2, $bar3], $barCollection->toArray());
        $this->assertEquals([$bar1, $bar2, $bar3], $sortedCollection->toArray());
    }

    public function testSortNameDescWithMethod()
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('getName', 'desc');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar1, $bar2, $bar3], $barCollection->toArray());
        $this->assertEquals([$bar1, $bar2, $bar3], $sortedCollection->toArray());
    }

    public function testSortNameWithInvalidProperty()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(1, 'b');

        $barCollection = new BarCollection([$bar1, $bar2]);

        $this->expectException(ValueExtractionException::class);
        $this->expectExceptionMessage('Method or property "unknown" not defined in Ramsey\Collection\Test\Mock\Bar');

        $barCollection->sort('unknown');
    }

    public function testUnknownSortOrderShouldRaiseException()
    {
        $barCollection = new BarCollection();

        $this->expectException(InvalidSortOrderException::class);
        $this->expectExceptionMessage('Invalid sort order given: bar');
        $barCollection->sort('fu', 'bar');
    }

    public function testFilter()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $filteredCollection = $barCollection->filter(function ($item) {
            return $item->name === 'a';
        });

        $this->assertNotSame($barCollection, $filteredCollection);
        $this->assertEquals([$bar1], $filteredCollection->toArray());
    }

    public function testWhereWithTypeSafePropertyValue()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('name', 'b');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertEquals([$bar2], $whereCollection->toArray());
    }

    public function testWhereWithTypeUnsafePropertyValue()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('id', '1');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertEquals([], $whereCollection->toArray());
    }

    public function testWhereWithTypeSafeMethodValue()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('getName', 'b');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertEquals([$bar2], $whereCollection->toArray());
    }

    public function testMapShouldRunOverEachItem()
    {
        $bar1 = $this->prophesize(Bar::class);
        $bar1->getName()->shouldBeCalled();
        $bar2 = $this->prophesize(Bar::class);
        $bar2->getName()->shouldBeCalled();

        $barCollection = new BarCollection([$bar1->reveal(), $bar2->reveal()]);

        $mapCollection = $barCollection->map(function (Bar $item) {
            $item->getName();
        });

        $this->assertNotSame($barCollection, $mapCollection);
    }

    public function testDiffShouldRaiseExceptionOnDiverseCollections()
    {
        $barCollection = new BarCollection();

        $this->expectException(DiverseCollectionException::class);
        $this->expectExceptionMessage('Collection must be of type Ramsey\Collection\Test\Mock\BarCollection');

        $barCollection->diff(new FooCollection());
    }

    public function testDiff()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar1, $bar2]);

        $diffCollection = $barCollection1->diff($barCollection2);

        $this->assertNotSame($diffCollection, $barCollection1);
        $this->assertNotSame($diffCollection, $barCollection2);
        $this->assertEquals([$bar2], $diffCollection->toArray());
    }

    public function testIntersectShouldRaiseExceptionOnDiverseCollections()
    {
        $barCollection = new BarCollection();

        $this->expectException(DiverseCollectionException::class);
        $this->expectExceptionMessage('Collection must be of type Ramsey\Collection\Test\Mock\BarCollection');

        $barCollection->intersect(new FooCollection());
    }

    public function testIntersect()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar1, $bar2]);

        $intersectCollection = $barCollection1->intersect($barCollection2);

        $this->assertNotSame($intersectCollection, $barCollection1);
        $this->assertNotSame($intersectCollection, $barCollection2);
        $this->assertEquals([$bar1], $intersectCollection->toArray());
    }

    public function testUniqueByObject()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection = new BarCollection([$bar1, $bar2, $bar1]);

        $uniqueCollection = $barCollection->unique();
        $this->assertNotSame($uniqueCollection, $barCollection);
        $this->assertEquals([$bar1, $bar2], $uniqueCollection->toArray());
    }

    public function testUniqueByProperty()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'b');

        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $uniqueCollection = $barCollection->unique('name');
        $this->assertNotSame($uniqueCollection, $barCollection);
        $this->assertEquals([$bar1, $bar2], $uniqueCollection->toArray());
    }

    public function testUniqueByMethod()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(1, 'c');

        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $uniqueCollection = $barCollection->unique('getId');
        $this->assertNotSame($uniqueCollection, $barCollection);
        $this->assertEquals([$bar1, $bar2], $uniqueCollection->toArray());
    }

    public function testUniqueEmptyData()
    {
        $barCollection = new BarCollection();

        $this->assertNotSame($barCollection->unique(), $barCollection);
    }

    public function testMergeShouldRaiseExceptionOnDiverseCollection()
    {
        $barCollection = new BarCollection();

        $this->expectException(DiverseCollectionException::class);
        $this->expectExceptionMessage(
            'Collection with index 1 must be of type Ramsey\Collection\Test\Mock\BarCollection'
        );

        $barCollection->merge(new BarCollection(), new FooCollection());
    }

    public function testMerge()
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar2]);
        $barCollection3 = new BarCollection([$bar3]);

        $mergeCollection = $barCollection1->merge($barCollection2, $barCollection3);
        $this->assertNotSame($mergeCollection, $barCollection1);
        $this->assertEquals([$bar1, $bar2, $bar3], $mergeCollection->toArray());
    }
}
