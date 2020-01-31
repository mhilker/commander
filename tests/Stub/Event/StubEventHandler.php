<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Event\EventHandler;
use Commander\Event\Events;
use SplQueue;

class StubEventHandler implements EventHandler
{
    private SplQueue $queue;

    public function __construct(callable ...$queue)
    {
        $this->queue = new SplQueue();
        foreach ($queue as $callable) {
            $this->queue->enqueue($callable);
        }
    }

    public function handle(Events $events): void
    {
        $callable = $this->queue->dequeue();
        $callable($events);
    }
}
