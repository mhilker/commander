<?php

declare(strict_types=1);

namespace MHilker\CQRS\EventStore;

use MHilker\CQRS\Aggregate\AggregateId;

interface EventStore
{
    public function store(StorableEvents $events): void;

    public function load(AggregateId $id): StorableEvents;
}
