<?php

declare(strict_types=1);

namespace MHilker\CQRS\EventStore;

use DateTimeImmutable;
use MHilker\CQRS\Aggregate\AggregateId;
use MHilker\CQRS\Event\Event;

interface StorableEvent extends Event
{
    public static function restore(array $data): StorableEvent;

    public function getAggregateId(): AggregateId;

    public function getOccurredOn(): DateTimeImmutable;

    public function getPayload(): array;
}
