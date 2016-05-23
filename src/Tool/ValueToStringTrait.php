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

namespace Ramsey\Collection\Tool;

/**
 * Provides functionality to express a value as string
 */
trait ValueToStringTrait
{
    /**
     * Return a string with the information of the value
     * null value: NULL
     * boolean: TRUE, FALSE
     * array: Array
     * scalar: converted-value
     * resource: (type resource #number)
     * object with __toString(): result of __toString()
     * object DateTime: ISO 8601 date
     * object: (className Object)
     * anonymous function: same as object
     *
     * @param mixed $value
     * @return string
     */
    protected function toolValueToString($value)
    {
        // null
        if (is_null($value)) {
            return 'NULL';
        }
        
        // boolean constants
        if (is_bool($value)) {
            return ($value) ? 'TRUE' : 'FALSE';
        }

        // array
        if (is_array($value)) {
            return 'Array';
        }

        // scalar types (integer, float, string)
        if (is_scalar($value)) {
            return (string) $value;
        }

        // resource
        if (is_resource($value)) {
            return '(' . get_resource_type($value) . ' resource #' . (int) $value . ')';
        }

        // after this line $value is an object since is not null, scalar, array or resource

        // __toString() is implemented
        if (is_callable([$value, '__toString'])) {
            return (string) $value->__toString();
        }

        // object of type \DateTime
        if ($value instanceof \DateTimeInterface) {
            return $value->format("c");
        }

        // unknown type
        return '(' . get_class($value) . ' Object)';
    }
}
