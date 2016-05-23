<?php
namespace Ramsey\Collection\Test\Tool;

use Ramsey\Collection\Test\TestCase;
use Ramsey\Collection\Tool\ValueToStringTrait;

/**
 * Tests for ValueToStringTraint
 */
class ValueToStringTraintTest extends TestCase
{
    
    use ValueToStringTrait;
    
    public function providerValueToStringMethod()
    {
        $function = function () {
            return;
        };
        $resource = opendir(__DIR__);
        return [
            
            // constant: null
            ['NULL', null],
            
            // constant: true
            ['TRUE', true],
            
            // constant: false
            ['FALSE', false],
            
            // constant: array
            ['Array', []],
            
            // scalar types
            ['0', 0],
            ['foo', 'foo'],
            
            // resource
            ['(stream resource #'. (int) $resource .')', $resource],
            
            // Object with __toString()
            ['BAZ', new Mock\ObjectWithToString()],
            
            // Object with invoke()
            ['(' . Mock\ObjectWithInvoke::class . ' Object)', new Mock\ObjectWithInvoke()],
            
            // Anonymous function
            ['(Closure Object)', $function],
            
            // Object DateTime
            ['2016-12-31T23:59:59+00:00', new \DateTime('2016-12-31T23:59:59+00:00')],
            
            // Object without __toString, expected class name
            ['(stdClass Object)', new \stdClass()],
        ];
    }

    /**
     * @dataProvider providerValueToStringMethod
     *
     * @param $expected
     * @param $value
     */
    public function testValueToStringMethod($expected, $value)
    {
        $this->assertEquals($expected, $this->toolValueToString($value));
    }
}