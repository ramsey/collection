<?php

// phpcs:disable

declare (strict_types=1);

namespace PHPStan\Testing;

use PHPStan\TrinaryLogic;

/**
 * @phpstan-pure
 * @param mixed $value
 * @return void
 *
 * @psalm-suppress UnusedParam
 */
function assertType(string $type, $value): void
{
}

/**
 * @phpstan-pure
 * @param mixed $value
 * @return void
 *
 * @psalm-suppress UnusedParam
 */
function assertNativeType(string $type, $value): void
{
}

/**
 * @phpstan-pure
 * @param mixed $variable
 * @return void
 *
 * @psalm-suppress UnusedParam
 */
function assertVariableCertainty(TrinaryLogic $certainty, $variable): void
{
}
