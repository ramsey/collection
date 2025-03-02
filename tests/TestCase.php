<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Faker\Factory;
use Faker\Generator;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TestCase extends MockeryTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }
}
