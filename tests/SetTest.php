<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Set;
use Ramsey\Collection\Test\Mock\Foo;

/**
 * Tests for Set class.
 *
 * As Set is a Collection with no duplicated elements
 * it only test the expected behavior.
 */
class SetTest extends TestCase
{
    /** @var Set<int> */
    private Set $set;

    protected function setUp(): void
    {
        parent::setUp();

        $this->set = new Set('int');
    }

    public function testConstructGetType(): void
    {
        $this->assertSame('int', $this->set->getType());
    }

    public function testConstructWithValues(): void
    {
        $expected = [2, 4, 6, 8];
        $localSet = new Set('int', $expected);
        $this->assertSame($expected, $localSet->toArray());
    }

    public function testAddDuplicates(): void
    {
        $this->assertTrue($this->set->add(100));
        $this->assertFalse($this->set->add(100));
        $this->assertSame([100], $this->set->toArray());
    }

    public function testOffsetSetDuplicates(): void
    {
        $this->set[] = 100;
        $this->set[] = 100;
        $this->assertSame([100], $this->set->toArray());
    }

    public function testUsingEqualButNotIdentical(): void
    {
        /** @var Set<Foo> $uniqueFoos */
        $uniqueFoos = new Set(Foo::class);

        // the comparisons are identical (===), not equal(==)
        $this->assertTrue($uniqueFoos->add(new Foo()));
        $this->assertTrue($uniqueFoos->add(new Foo()));
    }

    public function testUsingIdentical(): void
    {
        /** @var Set<Foo> $uniqueFoos */
        $uniqueFoos = new Set(Foo::class);

        // the comparisons are identical (===), not equal(==)
        $identical = new Foo();
        $this->assertTrue($uniqueFoos->add($identical));
        $this->assertFalse($uniqueFoos->add($identical));
    }

    public function testMergingSets(): void
    {
        /** @var Set<string> $set1 */
        $set1 = new Set('string', ['X', 'Y']);

        /** @var Set<string> $set2 */
        $set2 = new Set('string', ['Y', 'Z']);

        /** @var Set<string> $set3 */
        $set3 = $set1->merge($set2);

        $this->assertSame(['X', 'Y', 'Z'], $set3->toArray());
    }

    public function testMergingSetsOfObjects(): void
    {
        $obj1 = new Foo();
        $obj2 = new Foo();
        $obj3 = new Foo();

        $set1 = new Set(Foo::class, [$obj1, $obj2]);
        $set2 = new Set(Foo::class, [$obj2, $obj3]);
        $set3 = $set1->merge($set2);

        $this->assertSame([$obj1, $obj2, $obj3], $set3->toArray());
    }

    /**
     * Sets don't normally have keys...
     *
     * If a set has keys, when attempting to add a value that a set already
     * contains, even if the key is different, the new value cannot be added
     * because a set cannot contain duplicate values.
     *
     * In this test, the resulting merged set does not contain the "c" key
     * because the set already contains $obj2 when it attempts to add $obj2
     * again.
     */
    public function testMergingSetsOfObjectsWithKeysAlternate1(): void
    {
        $obj1 = new Foo();
        $obj2 = new Foo();
        $obj3 = new Foo();

        $set1 = new Set(Foo::class, ['a' => $obj1, 'b' => $obj2]);
        $set2 = new Set(Foo::class, ['c' => $obj2, 'd' => $obj3]);
        $set3 = $set1->merge($set2);

        $this->assertSame(['a' => $obj1, 'b' => $obj2, 'd' => $obj3], $set3->toArray());
    }

    /**
     * Sets don't normally have keys...
     *
     * According to standard array merging rules, later values for the same
     * string keys will overwrite previous ones. This rule is in effect here,
     * but with a set, this can also cause loss of data, depending on the order
     * of the values being merged.
     *
     * In this test, $obj2 does not appear in the merged collection because of
     * the order in which the merging occurs. First, $obj2 is found at key "b."
     * When merging the second set, we see $obj2 at key "c," but we find that
     * our set already contains $obj2, so we don't try to add it again. Then, we
     * merge $obj3 to the key "b," which overwrites $obj2.
     */
    public function testMergingSetsOfObjectsWithKeysAlternate2(): void
    {
        $obj1 = new Foo();
        $obj2 = new Foo();
        $obj3 = new Foo();

        $set1 = new Set(Foo::class, ['a' => $obj1, 'b' => $obj2]);
        $set2 = new Set(Foo::class, ['c' => $obj2, 'b' => $obj3]);
        $set3 = $set1->merge($set2);

        $this->assertSame(['a' => $obj1, 'b' => $obj3], $set3->toArray());
    }

    /**
     * Sets don't normally have keys...
     *
     * This test shows how order can affect behavior when merging. It is very
     * similar to {@see SetTest::testMergingSetsOfObjectsWithKeysAlternate2()},
     * but $set2 now contains keys "b" and "c" in alphabetical order, so when
     * merging, $obj3 is stored to key "b" and overwrites $obj2 that was
     * previously stored there. When we encounter key "c" (which also has $obj2),
     * this value no longer exists in the merged array, so can add it, and there
     * won't be a duplicate.
     */
    public function testMergingSetsOfObjectsWithKeysAlternate3(): void
    {
        $obj1 = new Foo();
        $obj2 = new Foo();
        $obj3 = new Foo();

        $set1 = new Set(Foo::class, ['a' => $obj1, 'b' => $obj2]);
        $set2 = new Set(Foo::class, ['b' => $obj3, 'c' => $obj2]);
        $set3 = $set1->merge($set2);

        $this->assertSame(['a' => $obj1, 'b' => $obj3, 'c' => $obj2], $set3->toArray());
    }
}
