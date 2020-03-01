<?php

declare(strict_types=1);

namespace Commander\Event;

use SplQueue;

final class MemoryEventPublisher implements EventPublisher
{
    /** @var SplQueue | Messages[] */
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function publish(Messages $messages): void
    {
        $this->queue->enqueue($messages);
    }

    public function dequeue(): Messages
    {
        return $this->queue->dequeue();
    }

    public function isEmpty(): bool
    {
        return $this->queue->isEmpty();
    }
}
