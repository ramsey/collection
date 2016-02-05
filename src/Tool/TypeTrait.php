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
 * Provides functionality to check values for specific types
 */
trait TypeTrait
{
    /**
     * Returns `true` if value is of the specified type
     *
     * @param string $type
     * @param mixed $value
     * @return bool
     */
    protected function checkType($type, $value)
    {
        switch ($type) {
            case 'array':
                return is_array($value);

            case 'bool':
            case 'boolean':
                return is_bool($value);

            case 'callable':
                return is_callable($value);

            case 'float':
            case 'double':
                return is_float($value);

            case 'int':
            case 'integer':
                return is_int($value);

            case 'null':
                return is_null($value);

            case 'numeric':
                return is_numeric($value);

            case 'object':
                return is_object($value);

            case 'resource':
                return is_resource($value);

            case 'scalar':
                return is_scalar($value);

            case 'string':
                return is_string($value);

            case 'mixed':
                return true;

            default:
                return ($value instanceof $type);
        }
    }
}
