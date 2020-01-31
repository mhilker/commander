<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Event\EventHandler;
use Commander\Event\Events;
use SplQueue;

class StubEventHandler implements EventHandler
{
    private SplQueue $callables;

    public function __construct(callable ...$callables)
    {
        $this->callables = new SplQueue();

        foreach ($callables as $callable) {
            $this->callables->enqueue($callable);
        }
    }

    public function handle(Events $events): void
    {
        $callable = $this->callables->dequeue();
        $callable($events);
    }
}
