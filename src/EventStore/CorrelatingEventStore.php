<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\UUID;

interface CorrelatingEventStore extends EventStore
{
    public function useCorrelationId(UUID $id): void;

    public function useCausationId(UUID $getId): void;
}
