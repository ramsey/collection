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

use Ramsey\Collection\Tool\TypeTrait;

/**
 * This class provides an implementation of the CollectionInterface, to
 * minimize the effort required to implement this interface
 */
abstract class AbstractCollection extends AbstractArray implements CollectionInterface
{
    use TypeTrait;

    /**
     * The type of elements stored in this collection
     *
     * A collection's type is immutable once it is set. For this reason, this
     * property is set private, and it is set in the abstract's constructor.
     * To create typed collections, override the constructor in your subclass
     * and pass the collection type to the parent constructor.
     *
     * @var string
     */
    private $collectionType;

    /**
     * Constructs a collection object of the specified type
     *
     * @param string $collectionType
     */
    public function __construct($collectionType)
    {
        $this->collectionType = $collectionType;
    }

    public function add($element)
    {
        $this[] = $element;

        return true;
    }

    public function contains($element)
    {
        return in_array($element, $this->data, true);
    }

    public function getType()
    {
        return $this->collectionType;
    }

    public function offsetSet($offset, $value)
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new \InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . var_export($value, true)
            );
        }

        $this->data[] = $value;
    }

    public function remove($element)
    {
        if (($position = array_search($element, $this->data, true)) !== false) {
            $this->offsetUnset($position);

            return true;
        }

        return false;
    }
}