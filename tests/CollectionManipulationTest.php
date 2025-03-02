<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Collection\Collection;
use Ramsey\Collection\Exception\CollectionMismatchException;
use Ramsey\Collection\Exception\InvalidPropertyOrMethod;
use Ramsey\Collection\Exception\UnsupportedOperationException;
use Ramsey\Collection\Sort;
use Ramsey\Collection\Test\Mock\Bar;
use Ramsey\Collection\Test\Mock\BarCollection;
use Ramsey\Collection\Test\Mock\FooCollection;

use function trim;

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

        $sortedCollection = $barCollection->sort('name', Sort::Descending);

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

        $sortedCollection = $barCollection->sort('getName', Sort::Descending);

        $this->assertNotSame($barCollection, $sortedCollection);
        $this->assertSame([$bar1, $bar2, $bar3], $sortedCollection->toArray());
        // Make sure original collection is untouched
        $this->assertSame([$bar1, $bar2, $bar3], $barCollection->toArray());
    }

    public function testSortWholeObject(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar2, $bar1, $bar3]);

        $sortedAsc = $barCollection->sort();
        $sortedDesc = $barCollection->sort(null, Sort::Descending);

        $this->assertNotSame($barCollection, $sortedAsc);
        $this->assertNotSame($barCollection, $sortedDesc);
        $this->assertNotSame($sortedAsc, $sortedDesc);
        $this->assertSame([$bar1, $bar2, $bar3], $sortedAsc->toArray());
        $this->assertSame([$bar3, $bar2, $bar1], $sortedDesc->toArray());
        $this->assertSame([$bar2, $bar1, $bar3], $barCollection->toArray());
    }

    public function testSortOnNonObjectCollection(): void
    {
        /** @var Collection<int> $collection */
        $collection = new Collection('int', [88, 23, 12, 42]);
        $sortedAsc = $collection->sort();
        $sortedDesc = $collection->sort(null, Sort::Descending);

        $this->assertNotSame($collection, $sortedAsc);
        $this->assertNotSame($collection, $sortedDesc);
        $this->assertNotSame($sortedAsc, $sortedDesc);
        $this->assertSame([88, 23, 12, 42], $collection->toArray());
        $this->assertSame([12, 23, 42, 88], $sortedAsc->toArray());
        $this->assertSame([88, 42, 23, 12], $sortedDesc->toArray());
    }

    public function testSortByArrayKey(): void
    {
        /** @var Collection<array{id: int, name: string}> $collection */
        $collection = new Collection('array', [
            ['id' => 2, 'name' => 'a'],
            ['id' => 1, 'name' => 'c'],
            ['id' => 3, 'name' => 'b'],
        ]);

        $sortedId = $collection->sort('id');
        $sortedName = $collection->sort('name', Sort::Descending);

        $this->assertNotSame($collection, $sortedId);
        $this->assertNotSame($collection, $sortedName);
        $this->assertNotSame($sortedId, $sortedName);
        $this->assertSame(
            [
                ['id' => 2, 'name' => 'a'],
                ['id' => 1, 'name' => 'c'],
                ['id' => 3, 'name' => 'b'],
            ],
            $collection->toArray(),
        );
        $this->assertSame(
            [
                ['id' => 1, 'name' => 'c'],
                ['id' => 2, 'name' => 'a'],
                ['id' => 3, 'name' => 'b'],
            ],
            $sortedId->toArray(),
        );
        $this->assertSame(
            [
                ['id' => 1, 'name' => 'c'],
                ['id' => 3, 'name' => 'b'],
                ['id' => 2, 'name' => 'a'],
            ],
            $sortedName->toArray(),
        );
    }

    public function testSortNameWithInvalidProperty(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(1, 'b');

        $barCollection = new BarCollection([$bar1, $bar2]);

        $this->expectException(InvalidPropertyOrMethod::class);
        $this->expectExceptionMessage('Method or property "unknown" not defined in Ramsey\Collection\Test\Mock\Bar');

        $barCollection->sort('unknown');
    }

    public function testSortNameWithInvalidArrayKey(): void
    {
        /** @var Collection<array{id: int, name: string}> $collection */
        $collection = new Collection('array', [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
            ['id' => 3, 'name' => 'c'],
        ]);

        $this->expectException(InvalidPropertyOrMethod::class);
        $this->expectExceptionMessage('Key or index "unknown" not found in collection elements');

        $collection->sort('unknown');
    }

    public function testSortShouldRaiseExceptionWhenNotSupported(): void
    {
        /** @var Collection<string> $collection */
        $collection = new Collection('string', ['a', 'b', 'c', 'd']);

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage(
            'The collection type "string" does not support the $propertyOrMethod parameter',
        );

        $collection->sort('foo');
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

    public function testWhereWithArrayKey(): void
    {
        /** @var Collection<array{id: int, name: string}> $collection */
        $collection = new Collection('array', [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
            ['id' => 3, 'name' => 'c'],
        ]);

        $where = $collection->where('id', 2);

        $this->assertNotSame($collection, $where);
        $this->assertSame(
            [
                ['id' => 2, 'name' => 'b'],
            ],
            $where->toArray(),
        );
        $this->assertSame(
            [
                ['id' => 1, 'name' => 'a'],
                ['id' => 2, 'name' => 'b'],
                ['id' => 3, 'name' => 'c'],
            ],
            $collection->toArray(),
        );
    }

    public function testWhereWithoutPropertyOrMethod(): void
    {
        /** @var Collection<array{id: int, name: string}> $collection */
        $collection = new Collection('array', [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
            ['id' => 3, 'name' => 'c'],
        ]);

        $where = $collection->where(null, ['id' => 3, 'name' => 'c']);

        $this->assertNotSame($collection, $where);
        $this->assertSame(
            [
                ['id' => 3, 'name' => 'c'],
            ],
            $where->toArray(),
        );
        $this->assertSame(
            [
                ['id' => 1, 'name' => 'a'],
                ['id' => 2, 'name' => 'b'],
                ['id' => 3, 'name' => 'c'],
            ],
            $collection->toArray(),
        );
    }

    public function testWhereWithScalar(): void
    {
        /** @var Collection<int> $collection */
        $collection = new Collection('int', [1, 2, 3, 4]);

        $where = $collection->where(null, 3);

        $this->assertNotSame($collection, $where);
        $this->assertSame([3], $where->toArray());
        $this->assertSame([1, 2, 3, 4], $collection->toArray());
    }

    public function testWhereWithMultipleObjectMatches(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $barCollection = new BarCollection([$bar1, $bar2, $bar3, $bar2]);

        $where = $barCollection->where(null, $bar2);

        $this->assertNotSame($barCollection, $where);
        $this->assertSame([$bar2, $bar2], $where->toArray());
        $this->assertSame([$bar1, $bar2, $bar3, $bar2], $barCollection->toArray());
    }

    public function testWhereShouldRaiseExceptionWhenNotSupported(): void
    {
        /** @var Collection<int> $collection */
        $collection = new Collection('int', [1, 2, 3, 4]);

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage(
            'The collection type "int" does not support the $propertyOrMethod parameter',
        );

        $collection->where('foo', 3);
    }

    public function testMapShouldRunOverEachItem(): void
    {
        $bar1 = $this->prophesize(Bar::class);
        $bar1->getName()->shouldBeCalled();
        $bar2 = $this->prophesize(Bar::class);
        $bar2->getName()->shouldBeCalled();

        $barCollection = new BarCollection([$bar1->reveal(), $bar2->reveal()]);

        $mapCollection = $barCollection->map(fn (Bar $item): string => $item->getName());

        $this->assertNotSame($barCollection, $mapCollection);
    }

    public function testReduceShouldRunOverEachItem(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');

        $barCollection = new BarCollection([$bar1, $bar2]);

        $reduced = $barCollection->reduce(
            fn (string $carry, Bar $item): string => trim("$carry {$item->getName()}"),
            '',
        );

        $this->assertSame('a b', $reduced);
    }

    public function testReduceShouldAllowNullInitialValue(): void
    {
        $bar1 = new Bar(1, 'a');
        $bar2 = new Bar(2, 'b');
        $bar3 = new Bar(3, 'c');
        $bar4 = new Bar(4, 'd');

        $barCollection = new BarCollection([$bar2, $bar3, $bar1, $bar4]);

        // Reduced should be the item with the lowest scoring name value.
        $reduced = $barCollection->reduce(
            function (?Bar $carry, Bar $item): Bar {
                if ($carry === null) {
                    return $item;
                }

                if ($carry->getName() < $item->getName()) {
                    return $carry;
                }

                return $item;
            },
            null,
        );

        $this->assertSame($bar1, $reduced);
    }

    public function testReduceWithAnEmptyCollectionA(): void
    {
        $barCollection = new BarCollection();

        $reduced = $barCollection->reduce(
            function (?Bar $carry, Bar $item): Bar {
                if ($carry === null) {
                    return $item;
                }

                if ($carry->getName() < $item->getName()) {
                    return $carry;
                }

                return $item;
            },
            null,
        );

        $this->assertNull($reduced);
    }

    public function testReduceWithAnEmptyCollectionB(): void
    {
        $barCollection = new BarCollection();

        $reduced = $barCollection->reduce(
            fn (string $carry, Bar $item): string => trim("$carry {$item->getName()}"),
            '',
        );

        $this->assertSame('', $reduced);
    }

    public function testDiffShouldRaiseExceptionOnDiverseCollections(): void
    {
        $barCollection = new BarCollection();

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage('Collection must be of type Ramsey\Collection\Test\Mock\BarCollection');

        /**
         * @phpstan-ignore-next-line
         */
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

    public function testDiffShouldRaiseExceptionOnDiverseCollectionType(): void
    {
        /** @var Collection<int> $barCollection */
        $barCollection = new Collection('int');

        /** @var Collection<string> $fooCollection */
        $fooCollection = new Collection('string');

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage('Collection items must be of type int');

        /**
         * @phpstan-ignore-next-line
         */
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

        /**
         * @phpstan-ignore-next-line
         */
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
        /** @var Collection<int> $barCollection */
        $barCollection = new Collection('int');

        /** @var Collection<string> $fooCollection */
        $fooCollection = new Collection('string');

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection items must be of type int',
        );

        /**
         * @phpstan-ignore-next-line
         */
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

        /**
         * @phpstan-ignore-next-line
         */
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
        /** @var Collection<int> $barCollection */
        $barCollection = new Collection('int');

        /** @var Collection<string> $fooCollection */
        $fooCollection = new Collection('string');

        $this->expectException(CollectionMismatchException::class);
        $this->expectExceptionMessage(
            'Collection items in collection with index 1 must be of type int',
        );

        /**
         * @phpstan-ignore-next-line
         */
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
        /** @var Collection<int> $collection1 */
        $collection1 = new Collection('integer', [1, 2, 3]);

        /** @var Collection<int> $collection2 */
        $collection2 = new Collection('int', [1, 2]);

        $this->assertEquals([3], $collection1->diff($collection2)->toArray());
        $this->assertEquals([1, 2], $collection1->intersect($collection2)->toArray());
        $this->assertEquals([1, 2, 3, 1, 2], $collection1->merge($collection2)->toArray());

        /** @var Collection<float> $collection1 */
        $collection1 = new Collection('float', [1.5, 2.5, 3.5]);

        /** @var Collection<float> $collection2 */
        $collection2 = new Collection('double', [1.5, 2.5]);

        $this->assertEquals([3.5], $collection1->diff($collection2)->toArray());
        $this->assertEquals([1.5, 2.5], $collection1->intersect($collection2)->toArray());
        $this->assertEquals([1.5, 2.5, 3.5, 1.5, 2.5], $collection1->merge($collection2)->toArray());

        /** @var Collection<bool> $collection1 */
        $collection1 = new Collection('bool', [true]);

        /** @var Collection<bool> $collection2 */
        $collection2 = new Collection('boolean', [false]);

        $this->assertEquals([true, false], $collection1->diff($collection2)->toArray());
        $this->assertEquals([], $collection1->intersect($collection2)->toArray());
        $this->assertEquals([true, false], $collection1->merge($collection2)->toArray());
    }
}
