<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Mock;

use Ramsey\Collection\AbstractCollection;

class FooCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Foo::class;
    }
}
