# ramsey/collection Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security

## [0.3.0] - 2016-05-23

### Added

* Add `MapInterface::keys()` method to return the keys from a MapInterface
  object. This was added to the AbstractMap class.

### Removed

* Removed `getType()` and constructor methods from AbstractCollection. Children
  of AbstractCollection must now implement `getType()`, which should return a
  string value that defines the data type of items for the collection.

### Fixed

* Improve error messages in exceptions when Collection and NamedParameterMap
  items fail type checks.

## [0.2.1] - 2016-02-22

### Fixed

* Allow non-strict checking of values in typed collections.

## [0.2.0] - 2016-02-05

### Added

* Support typed collections.

## [0.1.0] - 2015-10-27

### Added

* Support generic arrays and maps.

[Unreleased]: https://github.com/ramsey/collection/compare/0.3.0...HEAD
[0.3.0]: https://github.com/ramsey/collection/compare/0.2.1...0.3.0
[0.2.1]: https://github.com/ramsey/collection/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/ramsey/collection/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/ramsey/collection/commits/0.1.0
