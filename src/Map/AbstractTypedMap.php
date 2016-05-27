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

namespace Ramsey\Collection\Map;

use Ramsey\Collection\Tool\TypeTrait;
use Ramsey\Collection\Tool\ValueToStringTrait;

/**
 * This class provides an implementation of the TypedMapInterface, to
 * minimize the effort required to implement this interface
 */
abstract class AbstractTypedMap extends AbstractMap implements TypedMapInterface
{
    use TypeTrait;
    use ValueToStringTrait;

    public function offsetSet($offset, $value)
    {
        if (false === $this->checkType($this->getKeyType(), $offset)) {
            throw new \InvalidArgumentException(
                'Key must be of type ' . $this->getKeyType() . '; key is '
                . $this->toolValueToString($offset)
            );
        }
        if (false === $this->checkType($this->getValueType(), $value)) {
            throw new \InvalidArgumentException(
                'Value must be of type ' . $this->getValueType() . '; value is '
                . $this->toolValueToString($value)
            );
        }
        parent::offsetSet($offset, $value);
    }
}
