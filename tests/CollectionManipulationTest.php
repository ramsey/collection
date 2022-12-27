<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Collection\Collection;
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
    use ProphecyTrait;

    public function testSortNameAscWithAscendingIdAndNames(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar3, $bar2, $bar1]);

        $sortedCollection = $barCollection->sort('name');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertSame([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar3, $bar2, $bar1], $barCollection->toArray());
    }

    public function testSortNameAscWithDescendingNames(): void
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('name');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertSame([$bar3, $bar2, $bar1], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSortNameDescWithDescendingNames(): void
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('name', 'desc');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertSame([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSortNameDescWithMethod(): void
    {
        $bar1 = new Bar(1, 'c');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'a');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3]);

        $sortedCollection = $barCollection->sort('getName', 'desc');

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertSame([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection->toArray());
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

        $filteredCollection = $barCollection->filter(fn ($item) => $item->name === 'a');

        $this->assertNotSame($barCollection, $filteredCollection);
        $this->assertSame([$bar1], $filteredCollection->toArray());

        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2], $barCollection->toArray());
    }

    public function testWhereWithTypeSafePropertyValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('name', 'b');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertSame([$bar2], $whereCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2], $barCollection->toArray());
    }

    public function testWhereWithTypeUnsafePropertyValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('id', '1');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertSame([], $whereCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2], $barCollection->toArray());
    }

    public function testWhereWithTypeSafeMethodValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $barCollection = new BarCollection([$bar1, $bar2]);

        $whereCollection = $barCollection->where('getName', 'b');

        $this->assertNotSame($barCollection, $whereCollection);
        $this->assertSame([$bar2], $whereCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2], $barCollection->toArray());
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

        // @phpstan-ignore-next-line
        $barCollection->diff(new FooCollection());
    }

    public function testDiff(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar1, $bar2]);
        $barCollection3 = new BarCollection([$bar2, $bar1]);

        $diffCollection1 = $barCollection1->diff($barCollection2);
        $diffCollection2 = $barCollection1->diff($barCollection3);

        $this->assertNotSame($diffCollection1, $barCollection1);
        $this->assertNotSame($diffCollection1, $barCollection2);
        $this->assertNotSame($diffCollection1, $barCollection3);
        $this->assertSame([$bar2], $diffCollection1->toArray());

        $this->assertNotSame($diffCollection2, $barCollection1);
        $this->assertNotSame($diffCollection2, $barCollection2);
        $this->assertNotSame($diffCollection2, $barCollection3);
        $this->assertSame([$bar2], $diffCollection2->toArray());

        // Make sure original collections are untouched
        $this->assertSame([$bar1], $barCollection1->toArray());
        $this->assertSame([$bar1, $bar2], $barCollection2->toArray());
        $this->assertSame([$bar2, $bar1], $barCollection3->toArray());
    }

    public function testdiffShouldRaiseExceptionOnDiverseCollectionType(): void
    {
        $barCollection = new Collection('int');
        $fooCollection = new Collection('string');

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection items must be of type int',
        );

        $barCollection->diff($fooCollection);
    }

    public function testDiffGenericCollection(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new Collection(Bar::class, [$bar1]);
        $barCollection2 = new Collection(Bar::class, [$bar1, $bar2]);

        $diffCollection = $barCollection1->diff($barCollection2);

        $this->assertSame([$bar2], $diffCollection->toArray());
    }

    public function testIntersectShouldRaiseExceptionOnDiverseCollections(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage('Collection must be of type Ramsey\Collection\Test\Mock\BarCollection');

        // @phpstan-ignore-next-line
        $barCollection->intersect(new FooCollection());
    }

    public function testIntersect(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');

        $barCollection1 = new BarCollection([$bar1, $bar2]);
        $barCollection2 = new BarCollection([$bar1, $bar2, $bar3]);
        $barCollection3 = new BarCollection([$bar3, $bar2, $bar1]);

        $intersectCollection1 = $barCollection1->intersect($barCollection2);
        $intersectCollection2 = $barCollection1->intersect($barCollection3);

        $this->assertNotSame($intersectCollection1, $barCollection1);
        $this->assertNotSame($intersectCollection1, $barCollection2);
        $this->assertNotSame($intersectCollection1, $barCollection3);
        $this->assertSame([$bar1, $bar2], $intersectCollection1->toArray());

        $this->assertNotSame($intersectCollection2, $barCollection1);
        $this->assertNotSame($intersectCollection2, $barCollection2);
        $this->assertNotSame($intersectCollection2, $barCollection3);
        $this->assertSame([$bar1, $bar2], $intersectCollection2->toArray());

        // Make sure original collections are untouched
        $this->assertSame([$bar1, $bar2], $barCollection1->toArray());
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection2->toArray());
        $this->assertSame([$bar3, $bar2, $bar1], $barCollection3->toArray());
    }

    public function testIntersectShouldRaiseExceptionOnDiverseCollectionType(): void
    {
        $barCollection = new Collection('int');
        $fooCollection = new Collection('string');

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection items must be of type int',
        );

        $barCollection->intersect($fooCollection);
    }

    public function testIntersectGenericCollection(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new Collection(Bar::class, [$bar1]);
        $barCollection2 = new Collection(Bar::class, [$bar1, $bar2]);

        $diffCollection = $barCollection1->intersect($barCollection2);

        $this->assertSame([$bar1], $diffCollection->toArray());
    }

    public function testMergeShouldRaiseExceptionOnDiverseCollection(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection with index 1 must be of type Ramsey\Collection\Test\Mock\BarCollection',
        );

        // @phpstan-ignore-next-line
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
        $this->assertSame([$bar1, $bar2, $bar3], $mergeCollection->toArray());

        // Make sure the original collections are untouched
        $this->assertSame([$bar1], $barCollection1->toArray());
        $this->assertSame([$bar2], $barCollection2->toArray());
        $this->assertSame([$bar3], $barCollection3->toArray());
    }

    public function testMergeWhenTheSameObjectAppearsInMultipleCollections(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');

        $barCollection1 = new BarCollection([$bar1]);
        $barCollection2 = new BarCollection([$bar2, $bar1]);
        $barCollection3 = new BarCollection([$bar3, $bar2]);

        $mergeCollection = $barCollection1->merge($barCollection2, $barCollection3);
        $this->assertSame([$bar1, $bar2, $bar1, $bar3, $bar2], $mergeCollection->toArray());
    }

    public function testMergeFunctionalityWithKeys(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');

        $barCollection1 = new BarCollection(['a' => $bar1]);
        $barCollection2 = new BarCollection(['b' => $bar2, 'c' => $bar1]);
        $barCollection3 = new BarCollection(['c' => $bar3, 'd' => $bar2]);

        $mergeCollection = $barCollection1->merge($barCollection2, $barCollection3);
        $this->assertSame(['a' => $bar1, 'b' => $bar2, 'c' => $bar3, 'd' => $bar2], $mergeCollection->toArray());
    }

    public function testMergeShouldRaiseExceptionOnDiverseCollectionType(): void
    {
        $barCollection = new Collection('int');
        $fooCollection = new Collection('string');

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection items in collection with index 1 must be of type int',
        );

        $barCollection->merge($barCollection, $fooCollection);
    }

    public function testMergeGenericCollection(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection1 = new Collection(Bar::class, [$bar1]);
        $barCollection2 = new Collection(Bar::class, [$bar2]);

        $diffCollection = $barCollection1->merge($barCollection2);

        $this->assertSame([$bar1, $bar2], $diffCollection->toArray());
    }

    public function testMapConvertsValues(): void
    {
        $bar1 = new Bar(1, 'Jane');
        $bar2 = new Bar(2, 'John');
        $bar3 = new Bar(3, 'Janice');

        $barCollection = new BarCollection();
        $barCollection[] = $bar1;
        $barCollection[] = $bar2;
        $barCollection[] = $bar3;

        $names = $barCollection->map(fn (Bar $bar): string => $bar->getName());

        $ids = $barCollection->map(fn (Bar $bar): int => $bar->getId());

        $this->assertSame(['Jane', 'John', 'Janice'], $names->toArray());
        $this->assertSame([1, 2, 3], $ids->toArray());
    }

    public function testWorksUniformlyWithTypeAliases(): void
    {
        $collection1 = new Collection('integer', [1, 2, 3]);
        $collection2 = new Collection('int', [1, 2]);

        $this->assertEquals([3], $collection1->diff($collection2)->toArray());
        $this->assertEquals([1, 2], $collection1->intersect($collection2)->toArray());
        $this->assertEquals([1, 2, 3, 1, 2], $collection1->merge($collection2)->toArray());

        $collection1 = new Collection('float', [1.5, 2.5, 3.5]);
        $collection2 = new Collection('double', [1.5, 2.5]);

        $this->assertEquals([3.5], $collection1->diff($collection2)->toArray());
        $this->assertEquals([1.5, 2.5], $collection1->intersect($collection2)->toArray());
        $this->assertEquals([1.5, 2.5, 3.5, 1.5, 2.5], $collection1->merge($collection2)->toArray());

        $collection1 = new Collection('bool', [true]);
        $collection2 = new Collection('boolean', [false]);

        $this->assertEquals([true, false], $collection1->diff($collection2)->toArray());
        $this->assertEquals([], $collection1->intersect($collection2)->toArray());
        $this->assertEquals([true, false], $collection1->merge($collection2)->toArray());
    }
}
