<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Tool;

use Ramsey\Collection\Exception\InvalidPropertyOrMethod;
use Ramsey\Collection\Test\TestCase;
use Ramsey\Collection\Tool\ValueExtractorTrait;

/**
 * Cover up all possible outcomes of the ValueExtractorTrait.
 */
class ValueExtractorTraitTest extends TestCase
{
    public function testShouldRaiseExceptionWhenPropertyOrMethodNotExist(): void
    {
        $test = new class {
            use ValueExtractorTrait;

            /**
             * @return mixed
             */
            public function __invoke(string $propertyOrMethod)
            {
                return $this->extractValue($this, $propertyOrMethod);
            }

            public function getType(): string
            {
                return 'foo';
            }
        };

        $this->expectException(InvalidPropertyOrMethod::class);
        $this->expectExceptionMessage('Method or property "undefinedMethod" not defined in');

        $test('undefinedMethod');
    }

    public function testShouldExtractValueByMethod(): void
    {
        $test = new class {
            use ValueExtractorTrait;

            /**
             * @return mixed
             */
            public function __invoke(string $propertyOrMethod)
            {
                return $this->extractValue($this, $propertyOrMethod);
            }

            public function testMethod(): string
            {
                return 'works!';
            }

            public function getType(): string
            {
                return 'bar';
            }
        };

        $this->assertSame('works!', $test('testMethod'), 'Could not extract value by method');
    }

    public function testShouldExtractValueByProperty(): void
    {
        $test = new class {
            use ValueExtractorTrait;

            public string $testProperty = 'works!';

            /**
             * @return mixed
             */
            public function __invoke(string $propertyOrMethod)
            {
                return $this->extractValue($this, $propertyOrMethod);
            }

            public function getType(): string
            {
                return 'baz';
            }
        };

        $this->assertSame('works!', $test('testProperty'), 'Could not extract value by property');
    }

    public function testShouldExtractValueByMethodWhenPrivatePropertyExistsWithSameName(): void
    {
        $test = new class {
            use ValueExtractorTrait;

            /**
             * @return mixed
             */
            public function __invoke(mixed $element, string $propertyOrMethod)
            {
                return $this->extractValue($element, $propertyOrMethod);
            }

            public function getType(): string
            {
                return 'fudge';
            }
        };

        $element = new class {
            private string $testProperty = 'works!';

            public function testProperty(): string
            {
                return $this->testProperty;
            }
        };

        $this->assertSame('works!', $test($element, 'testProperty'), 'Could not extract value by method');
    }

    public function testShouldExtractValueByPropertyWhenPrivateMethodExistsWithSameName(): void
    {
        $test = new class {
            use ValueExtractorTrait;

            /**
             * @return mixed
             */
            public function __invoke(mixed $element, string $propertyOrMethod)
            {
                return $this->extractValue($element, $propertyOrMethod);
            }

            public function getType(): string
            {
                return 'fudge';
            }
        };

        $element = new class {
            public string $testProperty = 'works!';

            /** @phpstan-ignore-next-line */
            private function testProperty(): string
            {
                return 'does not work!';
            }
        };

        $this->assertSame('works!', $test($element, 'testProperty'), 'Could not extract value by property');
    }
}
