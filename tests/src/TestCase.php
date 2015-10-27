<?php
namespace Ramsey\Collection\Test;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    protected function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }
}
