<?php

declare(strict_types=1);

namespace MHilker\EventSourcing\Repository;

use MHilker\EventSourcing\AbstractAggregateRoot;
use MHilker\EventSourcing\AggregateId;

interface AggregateRepositoryInterface
{
    public function save(AbstractAggregateRoot $aggregate): void;

    public function load(AggregateId $id): AbstractAggregateRoot;
}
