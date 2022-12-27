<?php

declare(strict_types=1);

namespace Ramsey\Test\Generics;

class Person
{
    /**
     * @phpstan-ignore-next-line
     */
    private Attributes $attributes;

    public function __construct(?Attributes $attributes = null)
    {
        $this->attributes = $attributes ?? new Attributes();
    }
}
