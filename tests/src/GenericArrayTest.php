<?php
namespace Ramsey\Collection\Test;

use Ramsey\Collection\GenericArray;
use Ramsey\Collection\Test\TestCase;

/**
 * Tests for GenericArray, as well as coverage for AbstractArray
 */
class GenericArrayTest extends TestCase
{
    public function testConstructWithNoParameters()
    {
        $genericArrayObject = new GenericArray();

        $this->assertInternalType('array', $genericArrayObject->toArray());
        $this->assertEmpty($genericArrayObject->toArray());
        $this->assertTrue($genericArrayObject->isEmpty());
    }

    public function testConstructWithArray()
    {
        $phpArray = ['foo' => 'bar', 'baz'];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertEquals($phpArray, $genericArrayObject->toArray());
        $this->assertFalse($genericArrayObject->isEmpty());
    }

    public function testGetIterator()
    {
        $genericArrayObject = new GenericArray();

        $this->assertInstanceOf(\ArrayIterator::class, $genericArrayObject->getIterator());
    }

    public function testArrayAccess()
    {
        $phpArray = ['foo' => 123];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertTrue(isset($genericArrayObject['foo']));
        $this->assertFalse(isset($genericArrayObject['bar']));
        $this->assertEquals($phpArray['foo'], $genericArrayObject['foo']);

        $genericArrayObject['bar'] = 456;
        unset($genericArrayObject['foo']);

        $this->assertEquals(456, $genericArrayObject['bar']);
        $this->assertFalse(isset($genericArrayObject['foo']));
    }

    public function testOffsetSetWithEmptyOffset()
    {
        $genericArrayObject = new GenericArray();
        $genericArrayObject[] = 123;

        $this->assertEquals(123, $genericArrayObject[0]);
    }

    /**
     * This serves to ensure that isset() called on the array object for an
     * offset with a NULL value has the same behavior has isset() called on
     * any standard PHP array offset with a NULL value.
     */
    public function testOffsetExistsWithNullValue()
    {
        $genericArrayObject = new GenericArray();
        $genericArrayObject['foo'] = null;

        $this->assertFalse(isset($genericArrayObject['foo']));
    }

    public function testSerializable()
    {
        $phpArray = ['foo' => 123, 'bar' => 456];
        $genericArrayObject = new GenericArray($phpArray);

        $genericArrayObjectSerialized = serialize($genericArrayObject);
        $genericArrayObject2 = unserialize($genericArrayObjectSerialized);

        $this->assertInstanceOf(\Ramsey\Collection\ArrayInterface::class, $genericArrayObject2);
        $this->assertEquals($genericArrayObject, $genericArrayObject2);
    }

    public function testCountable()
    {
        $phpArray = ['foo' => 123, 'bar' => 456];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertEquals(count($phpArray), count($genericArrayObject));
    }

    public function testClear()
    {
        $phpArray = ['foo' => 'bar'];
        $genericArrayObject = new GenericArray($phpArray);

        $this->assertEquals($phpArray, $genericArrayObject->toArray());

        $genericArrayObject->clear();

        $this->assertEmpty($genericArrayObject->toArray());
    }
}
