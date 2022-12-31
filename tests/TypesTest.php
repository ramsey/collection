<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Generator;
use PHPStan\Testing\TypeInferenceTestCase;

use function glob;

class TypesTest extends TypeInferenceTestCase
{
    public function typeFileAsserts(): Generator
    {
        $typeTests = glob(__DIR__ . '/types/*.php') ?: [];

        foreach ($typeTests as $typeTest) {
            yield from $this->gatherAssertTypes($typeTest);
        }
    }

    /**
     * @dataProvider typeFileAsserts
     */
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
