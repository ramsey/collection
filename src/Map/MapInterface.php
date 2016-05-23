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

use Ramsey\Collection\ArrayInterface;

/**
 * An object that maps keys to values
 *
 * A map cannot contain duplicate keys; each key can map to at most one value.
 */
interface MapInterface extends ArrayInterface
{
    /**
     * Returns `true` if this map contains a mapping for the specified key
     *
     * @param mixed $key
     * @return bool
     */
    public function containsKey($key);

    /**
     * Returns `true` if this map maps one or more keys to the specified value
     *
     * This performs a strict type check on the value.
     *
     * @param mixed $value
     * @return bool
     */
    public function containsValue($value);

    /**
     * Return an array of the keys contained in this map
     *
     * @return array
     */
    public function keys();

    /**
     * Returns the value to which the specified key is mapped, `null` if this
     * map contains no mapping for the key, or (optionally) `$defaultValue` if
     * this map contains no mapping for the key
     *
     * @param mixed $key
     * @param mixed $defaultValue
     * @return mixed|null
     */
    public function get($key, $defaultValue = null);

    /**
     * Associates the specified value with the specified key in this map
     *
     * If the map previously contained a mapping for the key, the old value is
     * replaced by the specified value.
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed the previous value associated with key, or null if there was no mapping for key
     */
    public function put($key, $value);

    /**
     * If the specified key is not already associated with a value (or is mapped
     * to null) associates it with the given value and returns null, else
     * returns the current value
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed the previous value associated with key, or null if there was no mapping for key
     */
    public function putIfAbsent($key, $value);

    /**
     * Removes the mapping for a key from this map if it is present
     *
     * @param mixed $key
     * @return mixed the previous value associated with key, or null if there was no mapping for key
     */
    public function remove($key);

    /**
     * Removes the entry for the specified key only if it is currently mapped to
     * the specified value
     *
     * This performs a strict type check on the value.
     *
     * @param mixed $key
     * @param mixed $value
     * @return bool true if the value was removed
     */
    public function removeIf($key, $value);

    /**
     * Replaces the entry for the specified key only if it is currently mapped
     * to some value
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed the previous value associated with key, or null if there was no mapping for key
     */
    public function replace($key, $value);

    /**
     * Replaces the entry for the specified key only if currently mapped to the
     * specified value
     *
     * This performs a strict type check on the value.
     *
     * @param mixed $key
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return bool true if the value was replaced
     */
    public function replaceIf($key, $oldValue, $newValue);
}
