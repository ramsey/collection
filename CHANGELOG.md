# ramsey/collection Changelog

## 0.3.0

_Released: 2016-05-23_

* BREAKING: Remove `getType()` and constructor from AbstractCollection. Children must now implement `getType()`, which should return a string value naming the data type of items for the collection.
* NEW: Add `MapInterface::keys()` method to return the keys from a MapInterface object. Also added to the AbstractMap class.
* Improve error messages in exceptions when Collection and NamedParameterMap items fail type checks.

## 0.2.1

_Released: 2016-02-22_

* Allow for non-strict checking of values in typed collections

## 0.2.0

_Released: 2016-02-05_

* Add support for typed collections

## 0.1.0

_Released: 2015-10-27_

* Initial release
