# ramsey/collection

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![HHVM Status][badge-hhvm]][hhvm]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

ramsey/collection is a PHP 5.6+ collections framework for representing and manipulating collections.

This project adheres to a [Contributor Code of Conduct][conduct]. By participating in this project and its community, you are expected to uphold this code.


## About

Much inspiration for this library came from the [Java Collections Framework][java].


## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run
the following command to install the package and add it as a requirement to
your project's `composer.json`:

```bash
composer require ramsey/collection
```


## Examples

A collection represents a group of objects. Each object in the collection is of a specific, defined type.

### Generic Collection
This is a direct implementation of CollectionInterface, provided for the sake of convenience.
``` php
$collection = new \Ramsey\Collection\Collection('My\\Foo');
$collection->add(new \My\Foo());
$collection->add(new \My\Foo());

foreach ($collection as $foo) {
    // Do something with $foo
}
```

### Typed Collection
It is preferable to subclass AbstractCollection to create your own typed collections. For example:

``` php
namespace My\Foo;

class FooCollection extends \Ramsey\Collection\AbstractCollection
{
    public function getType()
    {
        return 'My\\Foo';
    }
}
```

And then use it similarly to the earlier example:

``` php
$fooCollection = new \My\Foo\FooCollection();
$fooCollection->add(new \My\Foo());
$fooCollection->add(new \My\Foo());

foreach ($fooCollection as $foo) {
    // Do something with $foo
}
```

One benefit of this approach is that you may do type-checking and type-hinting on the collection object.

``` php
if ($collection instanceof \My\Foo\FooCollection) {
    // the collection is a collection of My\Foo objects
}
```

#### Instantiating from an array of objects
In addition to `add`, you can also create a Typed Collection from an array of objects.

``` php
$foos = [
  new \My\Foo(),
  new \My\Foo()
];

$fooCollection = new \My\Foo\FooCollection($foos);
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.


## Copyright and License

The ramsey/collection library is copyright © [Ben Ramsey](https://benramsey.com/) and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.



[conduct]: https://github.com/ramsey/collection/blob/master/CODE_OF_CONDUCT.md
[java]: http://docs.oracle.com/javase/8/docs/technotes/guides/collections/index.html
[packagist]: https://packagist.org/packages/ramsey/collection
[composer]: http://getcomposer.org/
[apidocs]: http://docs.benramsey.com/ramsey-collection/latest/
[contributing]: https://github.com/ramsey/collection/blob/master/CONTRIBUTING.md

[badge-source]: http://img.shields.io/badge/source-ramsey/collection-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/ramsey/collection.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/ramsey/collection/master.svg?style=flat-square
[badge-hhvm]: https://img.shields.io/hhvm/ramsey/collection.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/ramsey/collection/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/ramsey/collection/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/ramsey/collection.svg?style=flat-square

[source]: https://github.com/ramsey/collection
[release]: https://github.com/ramsey/collection/releases
[license]: https://github.com/ramsey/collection/blob/master/LICENSE
[build]: https://travis-ci.org/ramsey/collection
[hhvm]: http://hhvm.h4cc.de/package/ramsey/collection
[quality]: https://scrutinizer-ci.com/g/ramsey/collection/
[coverage]: https://coveralls.io/r/ramsey/collection?branch=master
[downloads]: https://packagist.org/packages/ramsey/collection
