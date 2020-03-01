<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\Event;

use Commander\Event\Event;
use Commander\Event\EventHandler;
use SplQueue;

final class StubEventHandler implements EventHandler
{
    private SplQueue $queue;

    public function __construct(callable ...$queue)
    {
        $this->queue = new SplQueue();
        foreach ($queue as $callable) {
            $this->queue->enqueue($callable);
        }
    }

    public function handle(Event $event): void
    {
        if ($this->queue->count() > 0) {
            $callable = $this->queue->dequeue();
            $callable($event);
        }
    }
}
