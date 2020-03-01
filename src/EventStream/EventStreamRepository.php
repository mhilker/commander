<?php

declare(strict_types=1);

namespace Commander\EventStream;

use Commander\EventStream\Exception\EventStreamNotFoundException;
use Commander\EventStream\Exception\EventStreamNotSavedException;
use Commander\ID\Identifier;

interface EventStreamRepository
{
    /**
     * @throws EventStreamNotSavedException
     */
    public function save(AbstractEventStream $stream): void;

    /**
     * @throws EventStreamNotFoundException
     */
    public function load(Identifier $id): AbstractEventStream;
}
