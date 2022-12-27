<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Tool;

use DateTimeImmutable;
use Ramsey\Collection\Test\TestCase;
use Ramsey\Collection\Test\Tool\Mock\ObjectWithInvoke;
use Ramsey\Collection\Test\Tool\Mock\ObjectWithToString;
use Ramsey\Collection\Tool\ValueToStringTrait;
use stdClass;

use function get_resource_type;
use function opendir;

/**
 * Tests for ValueToStringTrait
 */
class ValueToStringTraitTest extends TestCase
{
    use ValueToStringTrait;

    public function testValueNull(): void
    {
        $this->assertSame('NULL', $this->toolValueToString(null));
    }

    public function testValueBoolean(): void
    {
        $this->assertSame('TRUE', $this->toolValueToString(true));
        $this->assertSame('FALSE', $this->toolValueToString(false));
    }

    public function testValueArray(): void
    {
        $this->assertSame('Array', $this->toolValueToString([]));
    }

    public function testValueScalar(): void
    {
        $this->assertSame('', $this->toolValueToString(''));
        $this->assertSame('foo', $this->toolValueToString('foo'));
        $this->assertSame('9', $this->toolValueToString(9));
    }

    public function testValueResource(): void
    {
        // get_resource_type behaves different on php and hhvm
        $resource = opendir(__DIR__);

        $this->assertIsResource($resource);

        $expected = '(' . get_resource_type($resource) . ' resource #' . (int) $resource . ')';

        $this->assertSame($expected, $this->toolValueToString($resource));
    }

    public function testValueObjectWithToString(): void
    {
        $this->assertSame('BAZ', $this->toolValueToString(new ObjectWithToString()));
    }

    public function testValueDateTime(): void
    {
        // datetimes are objects but are returned as iso dates, not as generic objects
        $expected = '2016-12-31T23:59:59+00:00';
        $date = new DateTimeImmutable('2016-12-31T23:59:59+00:00');
        $this->assertSame($expected, $this->toolValueToString($date));
    }

    public function testValueObject(): void
    {
        $expected = '(stdClass Object)';

        $value = new stdClass();
        $casted = $this->toolValueToString($value);

        $this->assertSame($expected, $casted);
    }

    public function testValueClosure(): void
    {
        // do not return a message like 'callable', cast it as object
        $startWith = '(Closure';
        $endsWith = ' Object)';

        $value = fn (): bool => false;
        $casted = $this->toolValueToString($value);

        $this->assertStringStartsWith($startWith, $casted);
        $this->assertStringEndsWith($endsWith, $casted);
    }

    public function testValueObjectWithInvoke(): void
    {
        // the object has a public __invoke method, is detected as callable
        // do not return a message like 'callable', cast it as object
        $startWith = '(' . ObjectWithInvoke::class . ' ';
        $endsWith = ' Object)';

        $value = new ObjectWithInvoke();
        $casted = $this->toolValueToString($value);

        $this->assertStringStartsWith($startWith, $casted);
        $this->assertStringEndsWith($endsWith, $casted);
    }
}
