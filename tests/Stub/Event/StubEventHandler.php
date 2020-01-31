<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Event\EventHandler;
use Commander\Event\Events;

class StubEventHandler implements EventHandler
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function handle(Events $events): void
    {
        ($this->callback)($events);
    }
}
