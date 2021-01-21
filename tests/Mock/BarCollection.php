<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Mock;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Bar>
 */
class BarCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Bar::class;
    }
}
