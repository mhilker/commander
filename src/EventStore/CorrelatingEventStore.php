<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Util\Identifier;

interface CorrelatingEventStore extends EventStore
{
    public function useCorrelationId(Identifier $id): void;

    public function useCausationId(Identifier $id): void;
}
