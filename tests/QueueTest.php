<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Mockery;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Queue;
use Ramsey\Collection\QueueInterface;

/**
 * @covers \Ramsey\Collection\Queue
 */
class QueueTest extends TestCase
{
    use QueueBehavior;

    /**
     * @param mixed[] $data
     */
    protected function queue(string $type, array $data = []): QueueInterface
    {
        return new Queue($type, $data);
    }

    public function testOfferReturnsFalseOnException(): void
    {
        $element = 'foo';

        $queue = Mockery::mock(Queue::class);
        $queue->shouldReceive('offer')->passthru();

        $queue->expects()->add($element)->andThrow(InvalidArgumentException::class);

        $this->assertFalse($queue->offer($element));
    }
}
