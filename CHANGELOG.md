# ramsey/collection Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.1.1 - 2025-03-23

### Fixed

* Correct the type annotation on `CollectionInterface::column()` to indicate the array
  it returns is `list<mixed>` ([#130](https://github.com/ramsey/collection/issues/130))

## 2.1.0 - 2025-03-02

### Added

* Add support for retrieving properties on collection items that are accessible via
  magic methods `__get` and `__isset` ([#126](https://github.com/ramsey/collection/pull/126))
* Certify support for PHP 8.3 and 8.4 ([#127](https://github.com/ramsey/collection/pull/127))

### Fixed

* Use the correct return type annotation of `list<T>` instead of `array<int, mixed>`
  for `CollectionInterface::column()` ([#124](https://github.com/ramsey/collection/issues/124))
* If an element has a property and method of the same name, check the property visibility
  on the element before attempting to access it; if it is private, attempt to call the
  method instead ([#123](https://github.com/ramsey/collection/pull/123))
* `ValueExtractorTrait` expects `getType(): string` to exist on the using class, but it did
  not declare an abstract to force this requirement; now it does, and any classes using this
  trait must implement `getType(): string`
* Avoid calling `contains()` twice when using `AbstractSet::add()`, significantly improving
  performance for very large collections ([#68](https://github.com/ramsey/collection/issues/68))

## 2.0.0 - 2022-12-31

### Added

* Add support for `CollectionInterface::reduce()` ([#87](https://github.com/ramsey/collection/pull/87))
* All exceptions now implement a base `CollectionException` interface
* Introduce `Sort` enum
* Support `column()`, `sort()`, and `where()` on non-object collection types

### Changed

* Minimum PHP version supported is 8.1
* Every method now has parameter and return type hints; if extending classes or
  implementing interfaces, you may need to change method signatures to upgrade
* The second parameter of `CollectionInterface::sort()` now uses the new `Sort`
  enum instead of a string
* Audit all template annotations and clean up Psalm and PHPStan types for
  correctness; if using static analysis in projects, this may require changes to
  your type annotations
* `ArrayInterface` no longer extends `\Serializable`, and the `serialize()` and
  `unserialize()` methods have been removed from `AbstractArray`; however,
  `AbstractArray` still supports serialization through implementing `__serialize()`
  and `__unserialize()`

## 1.3.0 - 2022-12-27

### Fixed

* Make type aliases compatible in diff, intersect, and merge ([#111](https://github.com/ramsey/collection/pull/111))
* Use `offsetUnset()` method to remove from the collection in `AbstractCollection` ([#104](https://github.com/ramsey/collection/pull/104))
* Use the correct base type of `array-key` for template `K` on `AbstractTypedMap`

### Changed

* Minimum PHP version supported is 7.4

## 1.2.2 - 2021-10-10

### Fixed

* Merging of sets now excludes duplicates, since a set does not allow duplicate
  values.

## 1.2.1 - 2021-08-05

### Fixed

* Standardize template annotations and fix iterable types

## 1.2.0 - 2021-08-05

### Added

* Support PHP 8.1.0

### Changed

* Minimum PHP version supported is 7.3

## 1.1.4 - 2021-07-29

### Fixed

* Add `Traversable<T>` return type to `getIterator()`.

## 1.1.3 - 2021-01-21

### Fixed

* Fixed incorrect callable type annotation on `CollectionInterface::map()`.

## 1.1.2 - 2021-01-20

### Fixed

* Fixed [Psalm](https://psalm.dev) annotations causing Psalm errors in
  downstream projects.
* Fixed `AbstractCollection::column()` attempting to access a property or method
  on a non-object.

## 1.1.1 - 2020-09-10

### Fixed

* Fixed broken `AbstractCollection::map()` implementation.

## 1.1.0 - 2020-08-10

### Fixed

* Fixed `AbstractCollection::diff()`, `AbstractCollection::intersect()` and
  `AbstractCollection::merge()` when used with Generic collections.
* Fixed `AbstractCollection::diff()` and `AbstractCollection::intersect()`
  returning inconsistent results when used on collections containing objects.
* Removed warning about deprecated dependency when running `composer install`

## 1.0.1 - 2020-01-04

### Fixed

* Fixed `AbstractCollection::offsetSet()` so that it uses the provided `$offset`
  when setting `$value` in the array.

## 1.0.0 - 2018-12-31

### Added

* Added support for *queue* data structures to represent collections of ordered
  entities. Together with *double-ended queues* (a.k.a. *deques*),
  first-in-first-out (FIFO), last-in-first-out (LIFO), and other queue and stack
  behaviors may be implemented. This functionality includes interfaces
  `QueueInterface` and `DoubleEndedQueueInterface` and classes `Queue` and
  `DoubleEndedQueue`.
* Added support for *set* data structures, representing collections that cannot
  contain any duplicated elements; includes classes `AbstractSet` and `Set`.
* Added support for *typed map* data structures to represent maps of elements
  where both keys and values have specified data types; includes
  `TypedMapInterface` and the classes `AbstractTypedMap` and `TypedMap`.
* Added new manipulation and analyze methods for collections: `column()`,
  `first()`, `last()`, `sort()`, `filter()`, `where()`, `map()`, `diff()`,
  `intersect()`, and `merge()`. See [CollectionInterface](https://github.com/ramsey/collection/blob/master/src/CollectionInterface.php)
  for more information.
* Added the following new exceptions specific to the ramsey/collection library:
  `CollectionMismatchException`, `InvalidArgumentException`,
  `InvalidSortOrderException`, `NoSuchElementException`, `OutOfBoundsException`,
  `UnsupportedOperationException`, and `ValueExtractionException`.

### Changed

* Minimum PHP version supported is 7.2.
* Strict types are enforced throughout.

### Removed

* Removed support for HHVM.

### Security

* Fixed possible exploit using `AbstractArray::unserialize()`
  (see [#47](https://github.com/ramsey/collection/issues/47)).

## 0.3.0 - 2016-05-23

### Added

* Added `MapInterface::keys()` method to return the keys from a `MapInterface`
  object. This was added to the `AbstractMap` class.

### Removed

* Removed `getType()` and constructor methods from `AbstractCollection`. Children
  of `AbstractCollection` must now implement `getType()`, which should return a
  string value that defines the data type of items for the collection.

### Fixed

* Improve error messages in exceptions when `Collection` and `NamedParameterMap`
  items fail type checks.

## 0.2.1 - 2016-02-22

### Fixed

* Allow non-strict checking of values in typed collections.

## 0.2.0 - 2016-02-05

### Added

* Support typed collections.

## 0.1.0 - 2015-10-27

### Added

* Support generic arrays and maps.
