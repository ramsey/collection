<?php
namespace Ramsey\Collection\Test\Map;

use Ramsey\Collection\Map\NamedParameterMap;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\TestCase;

/**
 * Tests for NamedParameterMap
 */
class NamedParameterMapTest extends TestCase
{
    public function testNamedParameters()
    {
        $inputParams = [
            'myArray' => 'array',
            'myBool' => 'bool',
            'myCallable' => 'callable',
            'myFloat' => 'float',
            'myDouble' => 'double',
            'myInt' => 'int',
            'myInteger' => 'integer',
            'myNull' => 'null',
            'myNumeric' => 'numeric',
            'myObject' => 'object',
            'myResource' => 'resource',
            'myScalar' => 'scalar',
            'myString' => 'string',
            'myFoo' => 'Ramsey\Collection\Test\Mock\Foo',
            'myMixed', // indexed array value in input
        ];

        $expectedParams = [
            'myArray' => 'array',
            'myBool' => 'bool',
            'myCallable' => 'callable',
            'myFloat' => 'float',
            'myDouble' => 'double',
            'myInt' => 'int',
            'myInteger' => 'integer',
            'myNull' => 'null',
            'myNumeric' => 'numeric',
            'myObject' => 'object',
            'myResource' => 'resource',
            'myScalar' => 'scalar',
            'myString' => 'string',
            'myFoo' => 'Ramsey\Collection\Test\Mock\Foo',
            'myMixed' => 'mixed',
        ];

        $namedParameterMap = new NamedParameterMap($inputParams);

        $namedParameterMap['myArray'] = $this->faker->words();
        $namedParameterMap['myBool'] = $this->faker->boolean();
        $namedParameterMap['myCallable'] = function () {
            return true;
        };
        $namedParameterMap['myFloat'] = $this->faker->randomFloat();
        $namedParameterMap['myDouble'] = $this->faker->randomFloat();
        $namedParameterMap['myInt'] = $this->faker->randomNumber();
        $namedParameterMap['myInteger'] = $this->faker->randomNumber();
        $namedParameterMap['myNull'] = null;
        $namedParameterMap['myNumeric'] = "{$this->faker->randomFloat()}";
        $namedParameterMap['myNumeric'] = "{$this->faker->randomNumber()}";
        $namedParameterMap['myObject'] = new \stdClass();
        $namedParameterMap['myResource'] = fopen('php://memory', 'r');
        $namedParameterMap['myScalar'] = $this->faker->name();
        $namedParameterMap['myScalar'] = $this->faker->randomNumber();
        $namedParameterMap['myScalar'] = $this->faker->randomFloat();
        $namedParameterMap['myScalar'] = $this->faker->boolean();
        $namedParameterMap['myString'] = $this->faker->text();
        $namedParameterMap['myFoo'] = new Foo();
        $namedParameterMap['myMixed'] = $this->faker->randomNumber();
        $namedParameterMap['myMixed'] = new Foo();
        $namedParameterMap['myMixed'] = null;
        $namedParameterMap['myMixed'] = fopen('php://memory', 'r');

        $this->assertEquals(
            $expectedParams,
            $namedParameterMap->getNamedParameters()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Attempting to set value for unconfigured parameter 'bar'
     */
    public function testNamedParametersWithUnnamedParameterThrowException()
    {
        $namedParameterMap = new NamedParameterMap(['foo']);
        $namedParameterMap['bar'] = 123;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value for 'foo' must be of type int
     */
    public function testNamedParametersWithWrongTypeThrowsException()
    {
        $namedParameterMap = new NamedParameterMap(['foo' => 'int']);
        $namedParameterMap['foo'] = $this->faker->text();
    }
}
