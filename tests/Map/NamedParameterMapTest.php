<?php
declare(strict_types=1);

namespace Ramsey\Collection\Test\Map;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Map\NamedParameterMap;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\TestCase;

/**
 * Tests for NamedParameterMap
 */
class NamedParameterMapTest extends TestCase
{
    public function testNamedParameters(): void
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
            'myFoo' => Foo::class,
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
            'myFoo' => Foo::class,
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
        $namedParameterMap['myNumeric1'] = (string)$this->faker->randomFloat();
        $namedParameterMap['myNumeric2'] = (string)$this->faker->randomNumber();
        $namedParameterMap['myObject'] = new \stdClass();
        $namedParameterMap['myResource'] = \fopen('php://memory', 'rb');
        $namedParameterMap['myScalar1'] = $this->faker->name();
        $namedParameterMap['myScalar2'] = $this->faker->randomNumber();
        $namedParameterMap['myScalar3'] = $this->faker->randomFloat();
        $namedParameterMap['myScalar4'] = $this->faker->boolean();
        $namedParameterMap['myString'] = $this->faker->text();
        $namedParameterMap['myFoo'] = new Foo();
        $namedParameterMap['myMixed1'] = $this->faker->randomNumber();
        $namedParameterMap['myMixed2'] = new Foo();
        $namedParameterMap['myMixed3'] = null;
        $namedParameterMap['myMixed4'] = \fopen('php://memory', 'rb');

        $this->assertEquals(
            $expectedParams,
            $namedParameterMap->getNamedParameters()
        );
    }

    public function testNamedParametersWithUnnamedParameterThrowException(): void
    {
        $namedParameterMap = new NamedParameterMap(['foo']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attempting to set value for unconfigured parameter \'bar\'');
        $namedParameterMap['bar'] = 123;
    }

    public function testNamedParametersWithWrongTypeThrowsException(): void
    {
        $namedParameterMap = new NamedParameterMap(['foo' => 'int']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value for \'foo\' must be of type int');
        $namedParameterMap['foo'] = $this->faker->text();
    }

    public function testNamedParameterWithNoStringValue(): void
    {
        $namedParameterMap = new NamedParameterMap(['foo' => 'int']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value for \'foo\' must be of type int');
        $namedParameterMap['foo'] = new \DateTime();
    }
}
