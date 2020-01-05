<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Map;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Map\TypedMap;
use Ramsey\Collection\Map\TypedMapInterface;
use Ramsey\Collection\Test\TestCase;

use function array_keys;

/**
 * Tests for TypedMap
 */
class TypedMapTest extends TestCase
{
    public function testConstructor(): void
    {
        $typed = new TypedMap('int', 'string');

        $this->assertInstanceOf(TypedMapInterface::class, $typed);
        $this->assertEquals('int', $typed->getKeyType());
        $this->assertEquals('string', $typed->getValueType());
        $this->assertEmpty($typed);
        $this->assertCount(0, $typed);
    }

    public function testConstructorWithValues(): void
    {
        $content = [0 => '0', 1 => '1', 2 => '4', 3 => '8', 4 => '16'];
        $keys = array_keys($content);
        $map = new TypedMap('int', 'string', $content);

        $this->assertEquals($keys, $map->keys());
        foreach ($keys as $key) {
            $this->assertEquals($content[$key], $map->get($key));
        }
    }

    public function testConstructorAddWrongKeyType(): void
    {
        $map = new TypedMap('string', 'mixed');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key must be of type string; key is');
        $map[9] = 'foo';
    }

    public function testConstructorAllowAddEmptyKey(): void
    {
        $map = new TypedMap('string', 'mixed');
        $map[''] = 'foo';
        $this->assertEquals([''], $map->keys());
    }

    public function testConstructorAddWrongValueType(): void
    {
        $map = new TypedMap('mixed', 'string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is');
        $map['foo'] = 9;
    }
}
