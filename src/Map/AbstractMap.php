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

use Ramsey\Collection\AbstractArray;

/**
 * This class provides an implementation of the MapInterface, to
 * minimize the effort required to implement this interface
 */
abstract class AbstractMap extends AbstractArray implements MapInterface
{
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            throw new \InvalidArgumentException(
                'Map elements are key/value pairs; a key must be provided for '
                . 'value ' . (string) $value
            );
        }

        $this->data[$offset] = $value;
    }

    public function containsKey($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function containsValue($value)
    {
        return in_array($value, $this->data, true);
    }

    public function keys()
    {
        return array_keys($this->data);
    }

    public function get($key, $defaultValue = null)
    {
        if (!$this->containsKey($key)) {
            return $defaultValue;
        }

        return $this->offsetGet($key);
    }

    public function put($key, $value)
    {
        $previousValue = $this->get($key);
        $this[$key] = $value;

        return $previousValue;
    }

    public function putIfAbsent($key, $value)
    {
        $currentValue = $this->get($key);

        if ($currentValue === null) {
            $this[$key] = $value;
        }

        return $currentValue;
    }

    public function remove($key)
    {
        $previousValue = $this->get($key);
        unset($this[$key]);

        return $previousValue;
    }

    public function removeIf($key, $value)
    {
        if ($this->get($key) === $value) {
            unset($this[$key]);

            return true;
        }

        return false;
    }

    public function replace($key, $value)
    {
        $currentValue = $this->get($key);

        if ($this->containsKey($key)) {
            $this[$key] = $value;
        }

        return $currentValue;
    }

    public function replaceIf($key, $oldValue, $newValue)
    {
        if ($this->get($key) === $oldValue) {
            $this[$key] = $newValue;

            return true;
        }

        return false;
    }
}
