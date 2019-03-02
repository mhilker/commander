<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;

interface EventStore
{
    public function store(StorableEvents $events): void;

    public function load(AggregateId $id): StorableEvents;
}
