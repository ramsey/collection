<?php
namespace Ramsey\Collection\Test\Tool;

use Ramsey\Collection\Test\TestCase;
use Ramsey\Collection\Tool\ValueToStringTrait;

/**
 * Tests for ValueToStringTrait
 */
class ValueToStringTraitTest extends TestCase
{
    
    use ValueToStringTrait;

    public function testValueNull()
    {
        $this->assertEquals('NULL', $this->toolValueToString(null));
    }

    public function testValueBoolean()
    {
        $this->assertEquals('TRUE', $this->toolValueToString(true));
        $this->assertEquals('FALSE', $this->toolValueToString(false));
    }

    public function testValueArray()
    {
        $this->assertEquals('Array', $this->toolValueToString([]));
    }

    public function testValueScalar()
    {
        $this->assertEquals('', $this->toolValueToString(''));
        $this->assertEquals('foo', $this->toolValueToString('foo'));
        $this->assertEquals('9', $this->toolValueToString(9));
    }

    public function testValueResource()
    {
        // get_resource_type behaves different on php and hhvm
        $resource = opendir(__DIR__);
        $expected = '(' . get_resource_type($resource) . ' resource #' . (int) $resource . ')';

        $this->assertEquals($expected, $this->toolValueToString($resource));
    }

    public function testValueObjectWithToString()
    {
        $this->assertEquals('BAZ', $this->toolValueToString(new Mock\ObjectWithToString()));
    }

    public function testValueDateTime()
    {
        // datetimes are objects but are returned as iso dates, not as generic objects
        $expected = '2016-12-31T23:59:59+00:00';
        $date = new \DateTimeImmutable('2016-12-31T23:59:59+00:00');
        $this->assertEquals($expected, $this->toolValueToString($date));
    }

    public function testValueObject()
    {
        $expected = '(stdClass Object)';

        $value = new \stdClass();
        $casted = $this->toolValueToString($value);

        $this->assertEquals($expected, $casted);
    }

    public function testValueClosure()
    {
        // do not return a message like 'callable', cast it as object
        $startWith = '(Closure';
        $endsWith = ' Object)';

        $value = function () {
            return;
        };
        $casted = $this->toolValueToString($value);

        $this->assertStringStartsWith($startWith, $casted);
        $this->assertStringEndsWith($endsWith, $casted);
    }

    public function testValueObjectWithInvoke()
    {
        // the object has a public __invoke method, is detected as callable
        // do not return a message like 'callable', cast it as object
        $startWith = '(' . Mock\ObjectWithInvoke::class . ' ';
        $endsWith = ' Object)';

        $value = new Mock\ObjectWithInvoke();
        $casted = $this->toolValueToString($value);

        $this->assertStringStartsWith($startWith, $casted);
        $this->assertStringEndsWith($endsWith, $casted);
    }
}
