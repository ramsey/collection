<?php

declare(strict_types=1);

namespace Ramsey\Test\Generics;

class Person
{
    public function __construct(public readonly string $name)
    {
    }
}
