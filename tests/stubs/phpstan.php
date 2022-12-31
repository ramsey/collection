<?php

// phpcs:disable

declare (strict_types=1);

namespace PHPStan\Testing;

use PHPStan\TrinaryLogic;

/**
 * @phpstan-pure
 * @param mixed $value
 * @return mixed
 *
 * @psalm-suppress UnusedParam
 */
function assertType(string $type, $value)
{
    return null;
}

/**
 * @phpstan-pure
 * @param mixed $value
 * @return mixed
 *
 * @psalm-suppress UnusedParam
 */
function assertNativeType(string $type, $value)
{
    return null;
}

/**
 * @phpstan-pure
 * @param mixed $variable
 * @return mixed
 *
 * @psalm-suppress UnusedParam
 */
function assertVariableCertainty(TrinaryLogic $certainty, $variable)
{
    return null;
}
