<?php

declare(strict_types=1);

namespace Commander\Event;

use SplQueue;

final class SplQueueEventPublisher implements EventPublisher
{
    /** @var SplQueue | Events[] */
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function publish(Events $events): void
    {
        $this->queue->enqueue($events);
    }

    public function dequeue(): Events
    {
        return $this->queue->dequeue();
    }

    public function count(): int
    {
        return $this->queue->count();
    }
}
