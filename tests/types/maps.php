<?php

// phpcs:disable

declare(strict_types=1);

namespace Ramsey\Collection\Test\types;

use Ramsey\Collection\Map\AssociativeArrayMap;

use Ramsey\Collection\Map\NamedParameterMap;
use Ramsey\Collection\Map\TypedMap;
use Ramsey\Collection\Test\Mock\Person;
use function PHPStan\Testing\assertType;

$associativeArray = new AssociativeArrayMap([
    'foo' => 1,
    'bar' => 'something',
    'baz' => false,
    'qux' => 23.3,
]);

assertType('Ramsey\Collection\Map\AssociativeArrayMap', $associativeArray);

assertType('mixed', $associativeArray['foo']);
assertType('mixed', $associativeArray['bar']);
assertType('mixed', $associativeArray['baz']);
assertType('mixed', $associativeArray['qux']);
assertType('mixed', $associativeArray->offsetGet('foo'));
assertType('array<string, mixed>', $associativeArray->toArray());
assertType('array<string, mixed>', $associativeArray->__serialize());
assertType('Traversable<string, mixed>', $associativeArray->getIterator());

foreach ($associativeArray as $key => $value) {
    assertType('string', $key);
    assertType('mixed', $value);
}

assertType('list<string>', $associativeArray->keys());
assertType('mixed', $associativeArray->get('foo'));
assertType('mixed', $associativeArray->put('foo', 'hello'));
assertType('mixed', $associativeArray->putIfAbsent('foo', 'hello'));
assertType('mixed', $associativeArray->remove('foo'));
assertType('mixed', $associativeArray->replace('foo', 'hello'));

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

foreach ($namedParameterMap as $key => $value) {
    assertType('string', $key);
    assertType('mixed', $value);
}

assertType('list<string>', $namedParameterMap->keys());
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

assertType('list<int>', $typedMap->keys());
assertType(Person::class . '|null', $typedMap->get(123));
assertType(Person::class . '|null', $typedMap->put(123, new Person('Jeffrey')));
assertType(Person::class . '|null', $typedMap->putIfAbsent(123, new Person('Jeffrey')));
assertType(Person::class . '|null', $typedMap->remove(123));
assertType(Person::class . '|null', $typedMap->replace(123, new Person('Jeffrey')));
