<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\ID\Identifier;

final class EventContext
{
    private Identifier $currentCorrelationId;
    private Identifier $currentCausationId;

    public function getCurrentCorrelationId(): Identifier
    {
        return $this->currentCorrelationId;
    }

    public function setCurrentCorrelationId(Identifier $currentCorrelationId): void
    {
        $this->currentCorrelationId = $currentCorrelationId;
    }

    public function getCurrentCausationId(): Identifier
    {
        return $this->currentCausationId;
    }

    public function setCurrentCausationId(Identifier $currentCausationId): void
    {
        $this->currentCausationId = $currentCausationId;
    }
}
