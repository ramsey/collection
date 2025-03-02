<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Map;

use DateTime;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Map\NamedParameterMap;
use Ramsey\Collection\Test\Mock\Foo;
use Ramsey\Collection\Test\TestCase;
use stdClass;

use function fopen;

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
            'myNumericFloat' => 'numeric',
            'myNumericInt' => 'numeric',
            'myObject' => 'object',
            'myResource' => 'resource',
            'myScalarString' => 'scalar',
            'myScalarInt' => 'scalar',
            'myScalarFloat' => 'scalar',
            'myScalarBool' => 'scalar',
            'myString' => 'string',
            'myFoo' => Foo::class,
            'myMixedInt',
            'myMixedObject',
            'myMixedNull',
            'myMixedResource',
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
            'myNumericFloat' => 'numeric',
            'myNumericInt' => 'numeric',
            'myObject' => 'object',
            'myResource' => 'resource',
            'myScalarString' => 'scalar',
            'myScalarInt' => 'scalar',
            'myScalarFloat' => 'scalar',
            'myScalarBool' => 'scalar',
            'myString' => 'string',
            'myFoo' => Foo::class,
            'myMixedInt' => 'mixed',
            'myMixedObject' => 'mixed',
            'myMixedNull' => 'mixed',
            'myMixedResource' => 'mixed',
        ];

        $namedParameterMap = new NamedParameterMap($inputParams);

        $namedParameterMap['myArray'] = $this->faker->words();
        $namedParameterMap['myBool'] = $this->faker->boolean();
        $namedParameterMap['myCallable'] = fn (): bool => true;
        $namedParameterMap['myFloat'] = $this->faker->randomFloat();
        $namedParameterMap['myDouble'] = $this->faker->randomFloat();
        $namedParameterMap['myInt'] = $this->faker->randomNumber();
        $namedParameterMap['myInteger'] = $this->faker->randomNumber();
        $namedParameterMap['myNull'] = null;
        $namedParameterMap['myNumericFloat'] = (string) $this->faker->randomFloat();
        $namedParameterMap['myNumericInt'] = (string) $this->faker->randomNumber();
        $namedParameterMap['myObject'] = new stdClass();
        $namedParameterMap['myResource'] = fopen('php://memory', 'rb');
        $namedParameterMap['myScalarString'] = $this->faker->name();
        $namedParameterMap['myScalarInt'] = $this->faker->randomNumber();
        $namedParameterMap['myScalarFloat'] = $this->faker->randomFloat();
        $namedParameterMap['myScalarBool'] = $this->faker->boolean();
        $namedParameterMap['myString'] = $this->faker->text();
        $namedParameterMap['myFoo'] = new Foo();
        $namedParameterMap['myMixedInt'] = $this->faker->randomNumber();
        $namedParameterMap['myMixedObject'] = new Foo();
        $namedParameterMap['myMixedNull'] = null;
        $namedParameterMap['myMixedResource'] = fopen('php://memory', 'rb');

        $this->assertSame(
            $expectedParams,
            $namedParameterMap->getNamedParameters(),
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
        $namedParameterMap['foo'] = new DateTime();
    }

    public function testNamedParametersWithNullKeyThrowsException(): void
    {
        $namedParameterMap = new NamedParameterMap(['foo']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Attempting to set value for unconfigured parameter 'NULL'");

        /**
         * @phpstan-ignore-next-line
         */
        $namedParameterMap[] = 123;
    }
}
