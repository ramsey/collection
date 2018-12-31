<?php
declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Faker\Generator;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TestCase extends MockeryTestCase
{
    /** @var Generator */
    protected $faker;

    protected function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }
}
