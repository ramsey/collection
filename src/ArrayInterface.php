<?php
/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://github.com/ramsey/collection GitHub
 */

declare(strict_types=1);

namespace Ramsey\Collection;

/**
 * `ArrayInterface` provides traversable array functionality to data types.
 */
interface ArrayInterface extends
    \ArrayAccess,
    \Countable,
    \IteratorAggregate,
    \Serializable
{
    /**
     * Removes all items from this array.
     */
    public function clear(): void;

    /**
     * Returns a native PHP array representation of this array object.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Returns `true` if this array is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
