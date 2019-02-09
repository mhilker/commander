<?php

declare(strict_types=1);

namespace MHilker\EventSourcing;

interface AggregateId
{
    public function toString(): string;
}
