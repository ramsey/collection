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

use Ramsey\Collection\Map\MapInterface;

/**
 * A `TypedMap` represent a Map of elements where key and value are typed.
 *
 * @package Ramsey\Collection\Map
 */
interface TypedMapInterface extends MapInterface
{
    /**
     * Return the type used on the key
     *
     * @return string
     */
    public function getKeyType();

    /**
     * Return the type forced on the values
     *
     * @return mixed
     */
    public function getValueType();
}
