<?php

declare(strict_types=1);

namespace MHilker\CQRS\Aggregate;

interface AggregateId
{
    public function asString(): string;
}
