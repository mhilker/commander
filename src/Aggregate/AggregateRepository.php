<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Aggregate\Exception\AggregateNotFoundException;
use Commander\Aggregate\Exception\AggregateNotSavedException;
use Commander\Identifier;

interface AggregateRepository
{
    /**
     * @throws AggregateNotSavedException
     */
    public function save(AbstractAggregate $aggregate): void;

    /**
     * @throws AggregateNotFoundException
     */
    public function load(Identifier $id): AbstractAggregate;
}
