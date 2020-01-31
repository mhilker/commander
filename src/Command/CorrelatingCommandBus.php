<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\EventStore\CorrelatingEventStore;

final class CorrelatingCommandBus implements CommandBus
{
    private CommandBus $commandBus;

    private CorrelatingEventStore $eventStore;

    public function __construct(CommandBus $commandBus, CorrelatingEventStore $eventStore)
    {
        $this->commandBus = $commandBus;
        $this->eventStore = $eventStore;
    }

    public function execute(Command $command): void
    {
        $this->eventStore->useCorrelationId($command->getId());
        $this->eventStore->useCausationId($command->getId());
        $this->commandBus->execute($command);
    }
}
