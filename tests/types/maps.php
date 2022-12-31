<?php

// phpcs:disable

declare(strict_types=1);

namespace Ramsey\Collection\Test\types;

use Ramsey\Collection\Map\AssociativeArrayMap;

use Ramsey\Collection\Map\NamedParameterMap;
use Ramsey\Collection\Map\TypedMap;
use Ramsey\Collection\Test\Mock\Person;
use function PHPStan\Testing\assertType;

/** @var AssociativeArrayMap<scalar> $associativeArray */
$associativeArray = new AssociativeArrayMap([
    'foo' => 1,
    'bar' => 'something',
    'baz' => false,
    'qux' => 23.3,
]);

assertType('Ramsey\Collection\Map\AssociativeArrayMap<bool|float|int|string>', $associativeArray);

assertType('bool|float|int|string', $associativeArray['foo']);
assertType('bool|float|int|string', $associativeArray['bar']);
assertType('bool|float|int|string', $associativeArray['baz']);
assertType('bool|float|int|string', $associativeArray['qux']);
assertType('bool|float|int|string', $associativeArray->offsetGet('foo'));
assertType('array<string, bool|float|int|string>', $associativeArray->toArray());
assertType('array<string, bool|float|int|string>', $associativeArray->__serialize());
assertType('Traversable<string, bool|float|int|string>', $associativeArray->getIterator());

foreach ($associativeArray as $key => $value) {
    assertType('string', $key);
    assertType('bool|float|int|string', $value);
}

assertType('array<int, string>', $associativeArray->keys());
assertType('bool|float|int|string|null', $associativeArray->get('foo'));
assertType('bool|float|int|string|null', $associativeArray->put('foo', 'hello'));
assertType('bool|float|int|string|null', $associativeArray->putIfAbsent('foo', 'hello'));
assertType('bool|float|int|string|null', $associativeArray->remove('foo'));
assertType('bool|float|int|string|null', $associativeArray->replace('foo', 'hello'));

$namedParameterMap = new NamedParameterMap(
    [
        'foo' => 'string',
        'bar' => Person::class,
    ],
    [
        'foo' => 'hello',
        'bar' => new Person('Jamie'),
    ],
);

assertType('Ramsey\Collection\Map\NamedParameterMap', $namedParameterMap);

assertType('mixed', $namedParameterMap['foo']);
assertType('mixed', $namedParameterMap['bar']);
assertType('mixed', $namedParameterMap->offsetGet('foo'));
assertType('array<string, mixed>', $namedParameterMap->toArray());
assertType('array<string, mixed>', $namedParameterMap->__serialize());
assertType('Traversable<string, mixed>', $namedParameterMap->getIterator());

/** @psalm-suppress MixedAssignment */
foreach ($namedParameterMap as $key => $value) {
    assertType('string', $key);
    assertType('mixed', $value);
}

assertType('array<int, string>', $namedParameterMap->keys());
assertType('mixed', $namedParameterMap->get('foo'));
assertType('mixed', $namedParameterMap->put('foo', 'goodbye'));
assertType('mixed', $namedParameterMap->putIfAbsent('foo', 'goodbye'));
assertType('mixed', $namedParameterMap->remove('foo'));
assertType('mixed', $namedParameterMap->replace('foo', 'goodbye'));

assertType('array<string, string>', $namedParameterMap->getNamedParameters());




$typedMap = new TypedMap('int', Person::class, [
    123 => new Person('Jason'),
    456 => new Person('Jackie'),
]);

assertType('Ramsey\Collection\Map\TypedMap<int, Ramsey\Collection\Test\Mock\Person>', $typedMap);

assertType(Person::class, $typedMap[123]);
assertType(Person::class, $typedMap[456]);
assertType(Person::class, $typedMap->offsetGet(123));
assertType('array<int, Ramsey\Collection\Test\Mock\Person>', $typedMap->toArray());
assertType('array<int, Ramsey\Collection\Test\Mock\Person>', $typedMap->__serialize());
assertType('Traversable<int, Ramsey\Collection\Test\Mock\Person>', $typedMap->getIterator());

foreach ($typedMap as $key => $value) {
    assertType('int', $key);
    assertType(Person::class, $value);
}

assertType('array<int, int>', $typedMap->keys());
assertType(Person::class . '|null', $typedMap->get(123));
assertType(Person::class . '|null', $typedMap->put(123, new Person('Jeffrey')));
assertType(Person::class . '|null', $typedMap->putIfAbsent(123, new Person('Jeffrey')));
assertType(Person::class . '|null', $typedMap->remove(123));
assertType(Person::class . '|null', $typedMap->replace(123, new Person('Jeffrey')));
