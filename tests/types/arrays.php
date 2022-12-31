<?php

// phpcs:disable

declare(strict_types=1);

namespace Ramsey\Collection\Test\types;

use DateTimeImmutable;
use Ramsey\Collection\GenericArray;
use Ramsey\Collection\Test\Mock\IntegerArray;
use Ramsey\Collection\Test\Mock\MyArray;
use Ramsey\Collection\Test\Mock\StringArray;
use stdClass;

use function PHPStan\Testing\assertType;

$genericArray = new GenericArray(['foo', 123, true, null, new stdClass(), new DateTimeImmutable()]);

assertType('mixed', $genericArray[0]);
assertType('mixed', $genericArray[1]);
assertType('mixed', $genericArray[2]);
assertType('mixed', $genericArray[3]);
assertType('mixed', $genericArray[4]);
assertType('mixed', $genericArray[5]);

/** @psalm-var mixed $value */
foreach ($genericArray as $value) {
    assertType('mixed', $value);
}

$stringArray = new StringArray(['foo', 'bar', 'baz']);

assertType('string', $stringArray[0]);
assertType('string', $stringArray[1]);
assertType('string', $stringArray[2]);

foreach ($stringArray as $value) {
    assertType('string', $value);
}

$integerArray = new IntegerArray([42, 56, 78]);

assertType('int', $integerArray[0]);
assertType('int', $integerArray[1]);
assertType('int', $integerArray[2]);

foreach ($integerArray as $value) {
    assertType('int', $value);
}

/** @var MyArray<array{id: int, name: string, date: DateTimeImmutable}> $myArray */
$myArray = new MyArray([
    [
        'id' => 1234,
        'name' => 'Samwise Gamgee',
        'date' => new DateTimeImmutable(),
    ],
]);

assertType('array{id: int, name: string, date: DateTimeImmutable}', $myArray[0]);
assertType('int', $myArray[0]['id']);
assertType('string', $myArray[0]['name']);
assertType(DateTimeImmutable::class, $myArray[0]['date']);
