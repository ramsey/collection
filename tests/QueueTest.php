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

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Queue;
use Ramsey\Collection\QueueInterface;

/**
 * @package Ramsey\Collection\Test
 * @covers \Ramsey\Collection\Queue
 */
class QueueTest extends TestCase
{
    use QueueBehavior;

    protected function queue(string $type, array $data = []): QueueInterface
    {
        return new Queue($type, $data);
    }
}
