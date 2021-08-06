<?php

declare(strict_types=1);

namespace Ramsey\Test\Generics;

class Person
{
    /**
     * @var Attributes
     */
    private $attributes;

    public function __construct(?Attributes $attributes = null)
    {
        $this->attributes = $attributes ?? new Attributes();
    }
}
