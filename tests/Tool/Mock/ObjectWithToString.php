<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Tool\Mock;

class ObjectWithToString
{
    public function __toString(): string
    {
        return 'BAZ';
    }
}
