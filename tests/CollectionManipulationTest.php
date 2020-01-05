<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Exception\CollectionMismatchException;
use Ramsey\Collection\Exception\InvalidSortOrderException;
use Ramsey\Collection\Exception\ValueExtractionException;
use Ramsey\Collection\Test\Mock\Bar;
use Ramsey\Collection\Test\Mock\BarCollection;
use Ramsey\Collection\Test\Mock\FooCollection;

/**
 * This test collection will test all manipulation methods on Collection.
 *
 * The nature of those manipulation methods is that the original collection stays untouched
 * and you will receive a new one. This is why every test MUST include a assertNotSame() assertion.
 */
class CollectionManipulationTest extends TestCase
{
    public function testSortNameAscWithAscendingIdAndNames(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar3, $bar2, $bar1]);

        $sortedCollection = $barCollection->sort('name');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar3, $bar2, $bar1], $barCollection->toArray());
    }

    public function testSortNameAscWithDescendingNames(): void
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('name');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar3, $bar2, $bar1], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSortNameDescWithDescendingNames(): void
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('name', 'desc');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSortNameDescWithMethod(): void
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('getName', 'desc');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertEquals([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSortNameWithInvalidProperty(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(1, 'b');

        $barCollection = new BarCollection([$bar1, $bar2]);

        $this->expectException(ValueExtractionException::class);
        $this->expectExceptionMessage('Method or property "unknown" not defined in Ramsey\Collection\Test\Mock\Bar');

        $barCollection->sort('unknown');
    }

    public function testUnknownSortOrderShouldRaiseException(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(InvalidSortOrderException::class);
        $this->expectExceptionMessage('Invalid sort order given: bar');
        $barCollection->sort('fu', 'bar');
    }

    public function testFilter(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $filteredCollection = $barCollection->filter(function ($item) {
            return $item->name === 'a';
        });

        $this->assertNotSame($barCollection, $filteredCollection);
        $this->assertEquals([$bar1], $filteredCollection->toArray());

        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2], $barCollection->toArray());
    }

    public function testWhereWithTypeSafePropertyValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('name', 'b');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertEquals([$bar2], $whereCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2], $barCollection->toArray());
    }

    public function testWhereWithTypeUnsafePropertyValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('id', '1');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertEquals([], $whereCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2], $barCollection->toArray());
    }

    public function testWhereWithTypeSafeMethodValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('getName', 'b');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertEquals([$bar2], $whereCollection->toArray());
        // Make sure original collection is untouched
        $this->assertEquals([$bar1, $bar2], $barCollection->toArray());
    }

    public function testMapShouldRunOverEachItem(): void
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

    public function testDiffShouldRaiseExceptionOnDiverseCollections(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage('Collection must be of type Ramsey\Collection\Test\Mock\BarCollection');

        $barCollection->diff(new FooCollection());
    }

    public function testDiff(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar1, $bar2]);

        $diffCollection = $barCollection1->diff($barCollection2);

        $this->assertNotSame($diffCollection, $barCollection1);
        $this->assertNotSame($diffCollection, $barCollection2);
        $this->assertEquals([$bar2], $diffCollection->toArray());
        // Make sure original collections are untouched
        $this->assertEquals([$bar1], $barCollection1->toArray());
        $this->assertEquals([$bar1, $bar2], $barCollection2->toArray());
    }

    public function testIntersectShouldRaiseExceptionOnDiverseCollections(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage('Collection must be of type Ramsey\Collection\Test\Mock\BarCollection');

        $barCollection->intersect(new FooCollection());
    }

    public function testIntersect(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar1, $bar2]);

        $intersectCollection = $barCollection1->intersect($barCollection2);

        $this->assertNotSame($intersectCollection, $barCollection1);
        $this->assertNotSame($intersectCollection, $barCollection2);
        $this->assertEquals([$bar1], $intersectCollection->toArray());
        // Make sure original collections are untouched
        $this->assertEquals([$bar1], $barCollection1->toArray());
        $this->assertEquals([$bar1, $bar2], $barCollection2->toArray());
    }

    public function testMergeShouldRaiseExceptionOnDiverseCollection(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection with index 1 must be of type Ramsey\Collection\Test\Mock\BarCollection'
        );

        $barCollection->merge(new BarCollection(), new FooCollection());
    }

    public function testMerge(): void
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

        // Make sure the original collections are untouched
        $this->assertEquals([$bar1], $barCollection1->toArray());
        $this->assertEquals([$bar2], $barCollection2->toArray());
        $this->assertEquals([$bar3], $barCollection3->toArray());
    }
}
