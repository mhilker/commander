<?php

declare(strict_types=1);

namespace MHilker\CQRS\Aggregate;

use MHilker\CQRS\Aggregate\Exception\AggregateNotFoundException;
use MHilker\CQRS\Aggregate\Exception\AggregateNotSavedException;

interface AggregateRepositoryInterface
{
    /**
     * @throws AggregateNotSavedException
     */
    public function save(AbstractAggregate $aggregate): void;

    /**
     * @throws AggregateNotFoundException
     */
    public function load(AggregateId $id): AbstractAggregate;
}
