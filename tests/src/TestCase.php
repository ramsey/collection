<?php
declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Faker\Generator;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    /** @var Generator */
    protected $faker;

    protected function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }
}
