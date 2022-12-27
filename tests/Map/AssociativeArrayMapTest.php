<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Map;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Map\AssociativeArrayMap;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\TestCase;

/**
 * Tests for AssociativeArrayMap, as well as coverage for AbstractMap
 */
class AssociativeArrayMapTest extends TestCase
{
    public function testOffsetSetWithEmptyOffsetThrowsException(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Map elements are key/value pairs; a key must be provided for value 123');
        $associativeArrayMapObject[] = 123;
    }

    public function testOffsetSetWithValidKey(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();
        $associativeArrayMapObject['foo'] = 123;

        $this->assertSame(123, $associativeArrayMapObject['foo']);
    }

    public function testContainsKey(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();
        $associativeArrayMapObject['foo'] = null;
        $associativeArrayMapObject['bar'] = 123;

        $this->assertFalse(isset($associativeArrayMapObject['foo']));
        $this->assertTrue($associativeArrayMapObject->containsKey('foo'));
        $this->assertTrue($associativeArrayMapObject->containsKey('bar'));
        $this->assertFalse($associativeArrayMapObject->containsKey('baz'));
    }

    public function testKeys(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();

        // empty map returns empty array
        $this->assertSame([], $associativeArrayMapObject->keys());
        $associativeArrayMapObject['foo'] = null;
        $associativeArrayMapObject['bar'] = 321;

        // array with key-value entries return array containing keys
        $this->assertSame(['foo', 'bar'], $associativeArrayMapObject->keys());
    }

    public function testContainsValue(): void
    {
        $foo = new Foo();

        $associativeArrayMapObject = new AssociativeArrayMap();
        $associativeArrayMapObject['foo'] = null;
        $associativeArrayMapObject['bar'] = 123;
        $associativeArrayMapObject['baz'] = $foo;

        $this->assertTrue($associativeArrayMapObject->containsValue(null));
        $this->assertTrue($associativeArrayMapObject->containsValue(123));
        $this->assertTrue($associativeArrayMapObject->containsValue($foo));
        $this->assertFalse($associativeArrayMapObject->containsValue(new Foo()));
    }

    public function testGet(): void
    {
        $data = ['foo' => 123];
        $associativeArrayMapObject = new AssociativeArrayMap($data);

        $this->assertSame($data['foo'], $associativeArrayMapObject->get('foo'));
        $this->assertFalse($associativeArrayMapObject->get('bar', false));
    }

    public function testPut(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();
        $previousValue = $associativeArrayMapObject->put('foo', 123);

        $this->assertNull($previousValue);

        $previousValue = $associativeArrayMapObject->put('foo', 456);

        $this->assertSame(123, $previousValue);

        // Ensure the value changed
        $this->assertSame(456, $associativeArrayMapObject->get('foo'));
    }

    public function testPutWithNullKeyThrowsException(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Map elements are key/value pairs; a key must be provided for value 123');

        /** @phpstan-ignore-next-line */
        $associativeArrayMapObject->put(null, 123);
    }

    public function testPutIfAbsent(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();
        $currentValue = $associativeArrayMapObject->putIfAbsent('foo', 123);

        $this->assertNull($currentValue);

        $currentValue = $associativeArrayMapObject->putIfAbsent('foo', 456);

        $this->assertSame(123, $currentValue);

        // Ensure the value does not change
        $this->assertSame(123, $associativeArrayMapObject->get('foo'));
    }

    public function testPutIfAbsentWithNullKeyThrowsException(): void
    {
        $associativeArrayMapObject = new AssociativeArrayMap();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Map elements are key/value pairs; a key must be provided for value 123');

        /** @phpstan-ignore-next-line */
        $associativeArrayMapObject->putIfAbsent(null, 123);
    }

    public function testRemove(): void
    {
        $data = ['foo' => 123];
        $associativeArrayMapObject = new AssociativeArrayMap($data);
        $previousValue = $associativeArrayMapObject->remove('foo');

        $this->assertSame($data['foo'], $previousValue);

        $previousValue = $associativeArrayMapObject->remove('foo');

        $this->assertNull($previousValue);
        $this->assertFalse($associativeArrayMapObject->containsKey('foo'));
    }

    public function testRemoveIf(): void
    {
        $data = ['foo' => 123];
        $associativeArrayMapObject = new AssociativeArrayMap($data);

        $this->assertFalse($associativeArrayMapObject->removeIf('foo', 456));
        $this->assertSame($data['foo'], $associativeArrayMapObject->get('foo'));
        $this->assertTrue($associativeArrayMapObject->removeIf('foo', 123));
        $this->assertFalse($associativeArrayMapObject->containsKey('foo'));
    }

    public function testReplace(): void
    {
        $data = ['foo' => 123];
        $associativeArrayMapObject = new AssociativeArrayMap($data);
        $previousValue = $associativeArrayMapObject->replace('foo', 456);

        $this->assertSame($data['foo'], $previousValue);
        $this->assertSame(456, $associativeArrayMapObject->get('foo'));

        $previousValue = $associativeArrayMapObject->replace('bar', 789);
        $this->assertNull($previousValue);
        $this->assertFalse($associativeArrayMapObject->containsKey('bar'));
    }

    public function testReplaceIf(): void
    {
        $data = ['foo' => 123];
        $associativeArrayMapObject = new AssociativeArrayMap($data);

        $this->assertFalse($associativeArrayMapObject->replaceIf('foo', 456, 789));
        $this->assertSame($data['foo'], $associativeArrayMapObject->get('foo'));
        $this->assertTrue($associativeArrayMapObject->replaceIf('foo', 123, 987));
        $this->assertSame(987, $associativeArrayMapObject->get('foo'));
    }
}
