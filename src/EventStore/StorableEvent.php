<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use DateTimeImmutable;

interface StorableEvent extends Event
{
    public static function restore(array $data): StorableEvent;

    public function getAggregateId(): AggregateId;

    public function getOccurredOn(): DateTimeImmutable;

    public function getPayload(): array;
}
