<?php

declare(strict_types=1);

namespace Commander\Unit\EventStream;

use Commander\Event\Event;
use Commander\EventStream\AbstractEventStream;
use Commander\ID\Identifier;

final class StubEventStream extends AbstractEventStream
{
    private Identifier $id;

    public static function create(Identifier $id): self
    {
        $eventStream = new self(null);
        $eventStream->record(new StubEvent($id));
        return $eventStream;
    }

    private function handle(StubEvent $event): void
    {
        $this->id = $event->getId();
    }

    protected function dispatch(Event $event): void
    {
        if ($event instanceof StubEvent) {
            $this->handle($event);
        }
    }

    protected function getEventStreamId(): Identifier
    {
        return $this->id;
    }
}
