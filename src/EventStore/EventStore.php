<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Messages;
use Commander\EventStore\Exception\EventStoreException;
use Commander\Identifier;

interface EventStore
{
    /**
     * @throws EventStoreException
     */
    public function store(Messages $messages): void;

    /**
     * @throws EventStoreException
     */
    public function load(Identifier $id): Messages;
}
