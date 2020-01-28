<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;
use Commander\EventStore\Exception\EventStoreException;

interface EventStore
{
    /**
     * @throws EventStoreException
     */
    public function store(StorableEvents $events): void;

    /**
     * @throws EventStoreException
     */
    public function load(AggregateId $id): StorableEvents;
}
