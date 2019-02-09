<?php

declare(strict_types=1);

namespace MHilker\EventSourcing;

interface AggregateId
{
    /**
     * @return string
     */
    public function asString(): string;
}
