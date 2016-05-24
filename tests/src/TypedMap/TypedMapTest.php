<?php
namespace Ramsey\Collection\Test\Map;

use Ramsey\Collection\TypedMap\TypedMap;
use Ramsey\Collection\TypedMap\TypedMapInterface;
use Ramsey\Collection\Test\TestCase;

/**
 * Tests for TypedMap
 */
class TypedMapTest extends TestCase
{
    public function testConstructor()
    {
        $typed = new TypedMap('int', 'string');

        $this->assertInstanceOf(TypedMapInterface::class, $typed);
        $this->assertEquals('int', $typed->getKeyType());
        $this->assertEquals('string', $typed->getValueType());
        $this->assertEmpty($typed);
        $this->assertCount(0, $typed);
    }

    public function testConstructorWithValues()
    {
        $content = [0 => '0', 1 => '1', 2 => '4', 3 => '8', 4 => '16'];
        $keys = array_keys($content);
        $map = new TypedMap('int', 'string', $content);

        $this->assertEquals($keys, $map->keys());
        foreach ($keys as $key) {
            $this->assertEquals($content[$key], $map->get($key));
        }
    }

    public function testConstructorAddWrongKeyType()
    {
        $map = new TypedMap('string', 'mixed');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Key must be of type string; key is');
        $map[9] = 'foo';
    }

    public function testConstructorAllowAddEmptyKey()
    {
        $map = new TypedMap('string', 'mixed');
        $map[''] = 'foo';
        $this->assertEquals([''], $map->keys());
    }

    public function testConstructorAddWrongValueType()
    {
        $map = new TypedMap('mixed', 'string');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is');
        $map['foo'] = 9;
    }
}
