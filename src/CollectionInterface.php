<?php
declare(strict_types=1);

/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://benramsey.com/projects/ramsey-collection/ Documentation
 * @link https://packagist.org/packages/ramsey/collection Packagist
 * @link https://github.com/ramsey/collection GitHub
 */

namespace Ramsey\Collection;

use Ramsey\Collection\Exception\DiverseCollectionException;
use Ramsey\Collection\Exception\InvalidSortOrderException;
use Ramsey\Collection\Exception\ValueExtractionException;

/**
 * A collection represents a group of objects, known as its elements. Some
 * collections allow duplicate elements and others do not. Some are ordered and
 * others unordered.
 */
interface CollectionInterface extends ArrayInterface
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';

    /**
     * Ensures that this collection contains the specified element (optional
     * operation). Returns true if this collection changed as a result of the
     * call. (Returns false if this collection does not permit duplicates and
     * already contains the specified element.)
     *
     * Collections that support this operation may place limitations on what
     * elements may be added to this collection. In particular, some
     * collections will refuse to add null elements, and others will impose
     * restrictions on the type of elements that may be added. Collection
     * classes should clearly specify in their documentation any restrictions
     * on what elements may be added.
     *
     * If a collection refuses to add a particular element for any reason other
     * than that it already contains the element, it must throw an exception
     * (rather than returning false). This preserves the invariant that a
     * collection always contains the specified element after this call returns.
     *
     * @param mixed $element
     * @return bool true if this collection changed as a result of the call
     */
    public function add($element): bool;

    /**
     * Returns `true` if this collection contains the specified element
     *
     * @param mixed $element
     * @param bool $strict Whether to perform a strict type check on the value.
     * @return bool
     */
    public function contains($element, bool $strict = true): bool;

    /**
     * Returns the type associated with this collection
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Removes a single instance of the specified element from this collection,
     * if it is present
     *
     * @param mixed $element
     * @return bool true if an element was removed as a result of this call
     */
    public function remove($element): bool;

    /**
     * Return the values from given property or method
     *
     * @param string $propertyOrMethod
     *
     * @return array
     * @throws ValueExtractionException Raised if property of method is not defined
     */
    public function column(string $propertyOrMethod): array;

    /**
     * Return the first item of the collection
     *
     * @return mixed
     */
    public function first();

    /**
     * Returns the last item of the collection
     *
     * @return mixed
     */
    public function last();

    /**
     * Sort the collection by property or method with given sort order
     *
     * This will always leave the original collection untouched and will return a new one.
     *
     * @param string $propertyOrMethod
     * @param string $order
     *
     * @return CollectionInterface
     * @throws InvalidSortOrderException Raised if neither ASC nor DESC was given
     */
    public function sort(string $propertyOrMethod, string $order = self::SORT_ASC): self;

    /**
     * Filter out items of the collection which don't math the criteria of given callback.
     *
     * This will always leave the original collection untouched and will return a new one.
     *
     * @param callable $callback
     *
     * @return CollectionInterface
     */
    public function filter(callable $callback): self;

    /**
     * Create a new collection where items match the criteria of given callback
     *
     * This will always leave the original collection untouched and will return a new one.
     *
     * @param string $propertyOrMethod
     * @param mixed  $value
     *
     * @return CollectionInterface
     */
    public function where(string $propertyOrMethod, $value): self;

    /**
     * Apply a given callback method on each item of the collection
     *
     * This will always leave the original collection untouched and will return a new one.
     *
     * @param callable $callback
     *
     * @return CollectionInterface
     */
    public function map(callable $callback): self;

    /**
     * Create a new collection with divergent items between current and given collection
     *
     * @param CollectionInterface $other
     *
     * @return CollectionInterface
     * @throws DiverseCollectionException Raised if given collection is not of same type
     */
    public function diff(CollectionInterface $other): self;

    /**
     * Create a new collection with intersecting item between current and given collection
     *
     * @param CollectionInterface $other
     *
     * @return CollectionInterface
     * @throws DiverseCollectionException Raised if given collection is not of same type
     */
    public function intersect(CollectionInterface $other): self;

    /**
     * Merge current items and items of given collections into a new one
     *
     * @param CollectionInterface[] ...$collections
     *
     * @return CollectionInterface
     * @throws DiverseCollectionException Raised if one of the collection is not of same type
     */
    public function merge(...$collections): self;
}
