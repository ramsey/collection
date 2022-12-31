<?php

// phpcs:disable

declare(strict_types=1);

namespace Ramsey\Collection\Test\types;

use Ramsey\Collection\Collection;
use Ramsey\Test\Generics\Person;

use function PHPStan\Testing\assertType;

$jane = new Person('Jane');
$john = new Person('John');
$janice = new Person('Janice');

$persons = new Collection(Person::class, [$jane, $john]);
$morePersons = new Collection(Person::class, [$john, $janice]);

assertType('Ramsey\Collection\Collection<Ramsey\Test\Generics\Person>', $persons);
assertType('Ramsey\Collection\Collection<Ramsey\Test\Generics\Person>', $morePersons);

assertType(Person::class, $persons[0]);
assertType('array<int, mixed>', $persons->column('name'));
assertType(Person::class, $persons->first());
assertType(Person::class, $persons->last());

assertType(
    'Ramsey\Collection\CollectionInterface<Ramsey\Test\Generics\Person>',
    $persons->sort(),
);

assertType(
    'Ramsey\Collection\CollectionInterface<Ramsey\Test\Generics\Person>',
    $persons->filter(fn (Person $person): bool => $person->name === 'Jane'),
);

assertType(
    'Ramsey\Collection\CollectionInterface<Ramsey\Test\Generics\Person>',
    $persons->where('name', 'Jane'),
);

assertType(
    'Ramsey\Collection\CollectionInterface<string>',
    $persons->map(fn (Person $person): string => $person->name),
);

assertType(
    'Ramsey\Collection\CollectionInterface<bool>',
    $persons->map(fn (Person $person): bool => isset($person->name)),
);

assertType(
    'string',
    $persons->reduce(fn (string $name, Person $person): string => "$name, $person->name", ''),
);

assertType(
    'bool',
    $persons->reduce(fn (bool $carry, Person $person): bool => $carry && isset($person->name), true),
);

assertType(
    'Ramsey\Collection\CollectionInterface<Ramsey\Test\Generics\Person>',
    $persons->diff($morePersons),
);

assertType(
    'Ramsey\Collection\CollectionInterface<Ramsey\Test\Generics\Person>',
    $persons->intersect($morePersons),
);

assertType(
    'Ramsey\Collection\CollectionInterface<Ramsey\Test\Generics\Person>',
    $persons->merge($morePersons),
);
