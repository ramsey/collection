<?php
declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Ramsey\Collection\Queue;
use Ramsey\Collection\QueueInterface;

/**
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
