<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Generator;
use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function glob;

class TypesTest extends TypeInferenceTestCase
{
    public static function typeFileAsserts(): Generator
    {
        $typeTests = glob(__DIR__ . '/types/*.php') ?: [];

        foreach ($typeTests as $typeTest) {
            yield from static::gatherAssertTypes($typeTest);
        }
    }

    #[DataProvider('typeFileAsserts')]
    public function testFileAsserts(string $assertType, string $file, mixed ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../phpstan.neon.dist'];
    }
}
