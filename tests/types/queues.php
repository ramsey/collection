<?php

// phpcs:disable

declare(strict_types=1);

namespace Ramsey\Collection\Test\types;

use Ramsey\Collection\Queue;
use Ramsey\Collection\Test\Mock\Person;

use function PHPStan\Testing\assertType;

$jane = new Person('Jane');
$john = new Person('John');

$persons = new Queue(Person::class, [$jane, $john]);

assertType('Ramsey\Collection\Queue<Ramsey\Collection\Test\Mock\Person>', $persons);

assertType(Person::class, $persons[0]);
assertType(Person::class, $persons[1]);
assertType(Person::class, $persons->offsetGet(0));
assertType('array<Ramsey\Collection\Test\Mock\Person>', $persons->toArray());
assertType('array<Ramsey\Collection\Test\Mock\Person>', $persons->__serialize());
assertType('Traversable<(int|string), Ramsey\Collection\Test\Mock\Person>', $persons->getIterator());

foreach ($persons as $person) {
    assertType(Person::class, $person);
}

assertType(Person::class, $persons->element());
assertType(Person::class . '|null', $persons->peek());
assertType(Person::class . '|null', $persons->poll());
assertType(Person::class, $persons->remove());
