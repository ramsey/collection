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
 * This class contains the logic to make a Collection to not allow duplicated values,
 * to minimize the effort required to implement this specific type of Collection.
 */
abstract class AbstractSet extends AbstractCollection
{
    /**
     * Adds the specified element to this set if it is not already present (optional operation).
     *
     * @param mixed $element
     * @return bool true if this set did not already contain the specified element
     */
    public function add($element)
    {
        if ($this->contains($element)) {
            return false;
        }
        return parent::add($element);
    }

    public function offsetSet($offset, $value)
    {
        if ($this->contains($value)) {
            return;
        }
        parent::offsetSet($offset, $value);
    }
}
