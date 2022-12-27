<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use ArrayIterator;
use Ramsey\Collection\ArrayInterface;
use Ramsey\Collection\GenericArray;

use function count;
use function serialize;
use function unserialize;

/**
 * Tests for GenericArray, as well as coverage for AbstractArray
 */
class GenericArrayTest extends TestCase
{
    public function testConstructWithNoParameters(): void
    {
        $genericArrayObject = new GenericArray();

        $this->assertIsArray($genericArrayObject->toArray());
        $this->assertEmpty($genericArrayObject->toArray());
        $this->assertTrue($genericArrayObject->isEmpty());
    }

    public function testConstructWithArray(): void
    {
        $phpArray = ['foo' => 'bar', 'baz'];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertSame($phpArray, $genericArrayObject->toArray());
        $this->assertFalse($genericArrayObject->isEmpty());
    }

    public function testGetIterator(): void
    {
        $genericArrayObject = new GenericArray();

        $this->assertInstanceOf(ArrayIterator::class, $genericArrayObject->getIterator());
    }

    public function testArrayAccess(): void
    {
        $phpArray = ['foo' => 123];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertTrue(isset($genericArrayObject['foo']));
        $this->assertFalse(isset($genericArrayObject['bar']));
        $this->assertSame($phpArray['foo'], $genericArrayObject['foo']);

        $genericArrayObject['bar'] = 456;
        unset($genericArrayObject['foo']);

        $this->assertSame(456, $genericArrayObject['bar']);
        $this->assertArrayNotHasKey('key', $genericArrayObject);
    }

    public function testOffsetSetWithEmptyOffset(): void
    {
        $genericArrayObject = new GenericArray();
        $genericArrayObject[] = 123;

        $this->assertSame(123, $genericArrayObject[0]);
    }

    /**
     * This serves to ensure that isset() called on the array object for an
     * offset with a NULL value has the same behavior has isset() called on
     * any standard PHP array offset with a NULL value.
     */
    public function testOffsetExistsWithNullValue(): void
    {
        $genericArrayObject = new GenericArray();
        $genericArrayObject['foo'] = null;

        $this->assertFalse(isset($genericArrayObject['foo']));
    }

    public function testSerializable(): void
    {
        $phpArray = ['foo' => 123, 'bar' => 456];
        $genericArrayObject = new GenericArray($phpArray);

        $genericArrayObjectSerialized = serialize($genericArrayObject);
        $genericArrayObject2 = unserialize($genericArrayObjectSerialized);

        $this->assertInstanceOf(ArrayInterface::class, $genericArrayObject2);
        $this->assertEquals($genericArrayObject, $genericArrayObject2);
    }

    public function testCountable(): void
    {
        $phpArray = ['foo' => 123, 'bar' => 456];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertCount(count($phpArray), $genericArrayObject);
    }

    public function testClear(): void
    {
        $phpArray = ['foo' => 'bar'];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertSame($phpArray, $genericArrayObject->toArray());

        $genericArrayObject->clear();

        $this->assertEmpty($genericArrayObject->toArray());
    }
}
