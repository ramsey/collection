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

/**
 * NamedParameterMap represents a mapping of values to a set of named keys
 * that may optionally be typed
 */
class NamedParameterMap extends AbstractMap
{
    /**
     * @var array
     */
    protected $namedParameters;

    /**
     * Constructs a new NamedParameterMap object
     *
     * @param array $namedParameters The named parameters supported
     * @param array $data
     */
    public function __construct(array $namedParameters, array $data = [])
    {
        $this->namedParameters = $this->filterNamedParameters($namedParameters);
        parent::__construct($data);
    }

    /**
     * Returns named parameters set for this NamedParameterMap
     *
     * @return array
     */
    public function getNamedParameters()
    {
        return $this->namedParameters;
    }

    public function offsetSet($offset, $value)
    {
        if (!array_key_exists($offset, $this->namedParameters)) {
            throw new \InvalidArgumentException(
                'Attempting to set value for unconfigured parameter \''
                . $offset . '\''
            );
        }

        if ($this->checkType($this->namedParameters[$offset], $value) === false) {
            throw new \InvalidArgumentException(
                'Value for \'' . $offset . '\' must be of type '
                . $this->namedParameters[$offset] . '; value is '
                . (string) $value
            );
        }

        $this->data[$offset] = $value;
    }


    /**
     * Given an array of named parameters, constructs a proper mapping of
     * named parameters to types
     *
     * @param array $namedParameters
     * @return array
     */
    protected function filterNamedParameters(array $namedParameters)
    {
        $names = [];
        $types = [];

        foreach ($namedParameters as $key => $value) {
            if (is_int($key)) {
                $names[] = (string) $value;
                $types[] = 'mixed';
            } else {
                $names[] = (string) $key;
                $types[] = (string) $value;
            }
        }

        return array_combine($names, $types);
    }

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
