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
 * Implementing this interface allows an object to be the target of the
 * "for-each loop" statement
 */
interface ArrayInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Remove all the elements from this array object
     *
     * @return void
     */
    public function clear();

    /**
     * Returns a native PHP array containing all of the elements in this array
     * object
     */
    public function toArray();

    /**
     * Returns `true` if this array object contains no elements
     *
     * @return bool
     */
    public function isEmpty();
}
