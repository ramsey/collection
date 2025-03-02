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
        /** @var TypedMap<int, string> $typed */
        $typed = new TypedMap('int', 'string');

        $this->assertInstanceOf(TypedMapInterface::class, $typed);
        $this->assertSame('int', $typed->getKeyType());
        $this->assertSame('string', $typed->getValueType());

        $this->assertEmpty($typed);

        $this->assertCount(0, $typed);
    }

    public function testConstructorWithValues(): void
    {
        $content = [0 => '0', 1 => '1', 2 => '4', 3 => '8', 4 => '16'];
        $keys = array_keys($content);

        /** @var TypedMap<int, string> $map */
        $map = new TypedMap('int', 'string', $content);

        $this->assertSame($keys, $map->keys());
        foreach ($keys as $key) {
            $this->assertSame($content[$key], $map->get($key));
        }
    }

    public function testConstructorAddWrongKeyType(): void
    {
        /** @var TypedMap<string, mixed> $map */
        $map = new TypedMap('string', 'mixed');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key must be of type string; key is');

        /**
         * @phpstan-ignore-next-line
         */
        $map[9] = 'foo';
    }

    public function testConstructorAllowAddEmptyKey(): void
    {
        /** @var TypedMap<string, mixed> $map */
        $map = new TypedMap('string', 'mixed');
        $map[''] = 'foo';
        $this->assertSame([''], $map->keys());
    }

    public function testConstructorAddWrongValueType(): void
    {
        /** @var TypedMap<string, string> $map */
        $map = new TypedMap('string', 'string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be of type string; value is');

        /**
         * @phpstan-ignore-next-line
         */
        $map['foo'] = 9;
    }

    public function testNullKeyRaisesException(): void
    {
        /** @var TypedMap<string, mixed> $map */
        $map = new TypedMap('string', 'mixed');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key must be of type string; key is NULL');

        /**
         * @phpstan-ignore-next-line
         */
        $map[] = 'foo';
    }
}
