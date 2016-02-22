<?php
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

/**
 * A collection represents a group of objects, known as its elements. Some
 * collections allow duplicate elements and others do not. Some are ordered and
 * others unordered.
 */
interface CollectionInterface extends ArrayInterface
{
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
    public function add($element);

    /**
     * Returns `true` if this collection contains the specified element
     *
     * @param mixed $element
     * @param bool $strict Whether to perform a strict type check on the value.
     * @return bool
     */
    public function contains($element, $strict = true);

    /**
     * Returns the type associated with this collection
     *
     * @return string
     */
    public function getType();

    /**
     * Removes a single instance of the specified element from this collection,
     * if it is present
     *
     * @param mixed $element
     * @return bool true if an element was removed as a result of this call
     */
    public function remove($element);
}
