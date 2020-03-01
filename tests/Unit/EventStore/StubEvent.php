<?php

declare(strict_types=1);

namespace Commander\Unit\EventStore;

use Commander\Event\Event;

final class StubEvent implements Event
{
    public static function fromPayload(array $payload): Event
    {
        return new self();
    }

    public function getPayload(): array
    {
        return [];
    }

    public function getTopic(): string
    {
        return 'topic';
    }

    public function getVersion(): int
    {
        return 1;
    }
}
