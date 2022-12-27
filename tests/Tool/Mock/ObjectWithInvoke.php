<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Tool\Mock;

class ObjectWithInvoke
{
    public function __invoke(): void
    {
    }
}
