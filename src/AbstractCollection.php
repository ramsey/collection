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

use Ramsey\Collection\Exception\CollectionMismatchException;
use Ramsey\Collection\Exception\InvalidSortOrderException;
use Ramsey\Collection\Exception\OutOfBoundsException;
use Ramsey\Collection\Tool\TypeTrait;
use Ramsey\Collection\Tool\ValueExtractorTrait;
use Ramsey\Collection\Tool\ValueToStringTrait;

/**
 * This class provides an implementation of the CollectionInterface, to
 * minimize the effort required to implement this interface
 */
abstract class AbstractCollection extends AbstractArray implements CollectionInterface
{
    use TypeTrait;
    use ValueToStringTrait;
    use ValueExtractorTrait;

    public function add($element): bool
    {
        $this[] = $element;

        return true;
    }

    public function contains($element, bool $strict = true): bool
    {
        return \in_array($element, $this->data, $strict);
    }

    public function offsetSet($offset, $value): void
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new \InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . $this->toolValueToString($value)
            );
        }

        $this->data[] = $value;
    }

    public function remove($element): bool
    {
        if (($position = array_search($element, $this->data, true)) !== false) {
            unset($this->data[$position]);


            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function column(string $propertyOrMethod): array
    {
        $temp = [];

        foreach ($this->data as $item) {
            $temp[] = $this->extractValue($item, $propertyOrMethod);
        }

        return $temp;
    }

    /**
     * @inheritDoc
     */
    public function first()
    {
        if (empty($this->data)) {
            throw new OutOfBoundsException('Can\'t determine first item. Collection is empty');
        }

        reset($this->data);

        return current($this->data);
    }

    /**
     * @inheritDoc
     */
    public function last()
    {
        if (empty($this->data)) {
            throw new OutOfBoundsException('Can\'t determine last item. Collection is empty');
        }

        $item = end($this->data);
        reset($this->data);

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function sort(string $propertyOrMethod, string $order = self::SORT_ASC): CollectionInterface
    {
        if (!\in_array($order, [self::SORT_ASC, self::SORT_DESC], true)) {
            throw new InvalidSortOrderException('Invalid sort order given: ' . $order);
        }

        $collection = clone $this;

        usort($collection->data, function ($a, $b) use ($propertyOrMethod, $order) {
            $aValue = $this->extractValue($a, $propertyOrMethod);
            $bValue = $this->extractValue($b, $propertyOrMethod);

            return ($aValue <=> $bValue) * ($order === self::SORT_DESC ? -1 : 1);
        });

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function filter(callable $callback): CollectionInterface
    {
        $collection = clone $this;
        $collection->data = array_merge([], array_filter($collection->data, $callback));

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function where(string $propertyOrMethod, $value): CollectionInterface
    {
        return $this->filter(function ($item) use ($propertyOrMethod, $value) {
            $accessorValue = $this->extractValue($item, $propertyOrMethod);

            return $accessorValue === $value;
        });
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback): CollectionInterface
    {
        $collection = clone $this;
        array_map($callback, $collection->data);

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function diff(CollectionInterface $other): CollectionInterface
    {
        if (!$other instanceof static) {
            throw new CollectionMismatchException('Collection must be of type ' . static::class);
        }

        $comparator = function ($a, $b) {
            return $a === $b ? 0 : -1;
        };

        $diffAtoB = array_udiff($this->data, $other->data, $comparator);
        $diffBtoA = array_udiff($other->data, $this->data, $comparator);

        return new static(array_merge($diffAtoB, $diffBtoA));
    }

    /**
     * @inheritDoc
     */
    public function intersect(CollectionInterface $other): CollectionInterface
    {
        if (!$other instanceof static) {
            throw new CollectionMismatchException('Collection must be of type ' . static::class);
        }

        $intersect = array_uintersect($this->data, $other->data, function ($a, $b) {
            return $a === $b ? 0 : -1;
        });

        return new static($intersect);
    }

    /**
     * @inheritDoc
     */
    public function merge(CollectionInterface ...$collections): CollectionInterface
    {
        $temp = [$this->data];

        foreach ($collections as $index => $collection) {
            if (!$collection instanceof static) {
                throw new CollectionMismatchException(
                    sprintf('Collection with index %d must be of type %s', $index, static::class)
                );
            }

            $temp[] = $collection->toArray();
        }

        return new static(array_merge(...$temp));
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized): void
    {
        $this->data = unserialize($serialized, ['allowed_classes' => [$this->getType()]]);
    }
}
