<?php

declare(strict_types=1);

namespace Ramsey\Test\Generics;

use Ramsey\Collection\Map\AbstractMap;
use Ramsey\Collection\Map\MapInterface;

/**
 * @extends AbstractMap<mixed>
 * @implements MapInterface<mixed>
 */
class Attributes extends AbstractMap implements MapInterface
{
}
