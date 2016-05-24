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

namespace Ramsey\Collection\TypedMap;

use Ramsey\Collection\Tool\TypeTrait;

/**
 * A `TypedMap` represent a Map of elements where key and value are typed.
 *
 * Each element is identified by a key with defined type and a value of defined type.
 * The keys of the map must be unique. The values on the map can be repeated but each
 * with its own different key.
 *
 * The most common case is to use a string type key. But it's not limited to this type if keys.
 *
 * This is a direct implementation of `TypedMapInterface`, provided for the sake of convenience.
 *
 * Example usage:
 *
 * ```php
 * $map = new TypedMap('string', Foo::class);
 * $map['x'] = new Foo();
 * foreach($map as $key => $value) {
 *     // do something with $key, it will be a Foo::class
 * }
 *
 * // this will throw an exception since key must be string
 * $map[10] = new Foo();
 *
 * // this will throw an exception since value must be a Foo
 * $map['bar'] = 'bar';
 *
 * // initialize map with contents
 * $map = new TypedMap('string', Foo::class, [
 *     new Foo(), new Foo(), new Foo()
 * ]);
 * ```
 *
 * It is preferable to subclass `AbstractTypedMap`
 * to create your own typed map implementation
 *
 * ```php
 * class FooTypedMap extends AbstractTypedMap
 * {
 *     public function getKeyType()
 *     {
 *         return "int"
 *     }
 *
 *     public function getValueType()
 *     {
 *          return Foo::class;
 *     }
 * }
 * ```
 *
 * ... but it is not limited, you also can use the `TypedMap` class
 *
 * ```php
 * class FooTypedMap extends TypedMap
 * {
 *     public function __constructor(array $data = [])
 *     {
 *         parent::__construct("int", Foo::class, $data);
 *     }
 * }
 * ```
 *
 *
 * @package Ramsey\Collection\Map
 */
class TypedMap extends AbstractTypedMap
{
    
    use TypeTrait;

    /**
     * The type of keys stored in this collection
     *
     * A map key's type is immutable once it is set. For this reason, this
     * property is set private
     *
     * @var string type of the map key
     */
    private $keyType;

    /**
     * The type of values stored in this collection
     *
     * A map values's type is immutable once it is set. For this reason, this
     * property is set private
     *
     * @var string type of the map value
     */
    private $valueType;
    
    /**
     * Constructs a map object of the specified key and value types,
     * optionally with the specified data
     *
     * @param string $keyType
     * @param string $valueType
     * @param array $data
     */
    public function __construct($keyType, $valueType, array $data = [])
    {
        $this->keyType = (string) $keyType;
        $this->valueType = (string) $valueType;
        parent::__construct($data);
    }

    public function getKeyType()
    {
        return $this->keyType;
    }
    
    public function getValueType()
    {
        return $this->valueType;
    }
}
