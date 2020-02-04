<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\EventStore\CorrelatingEventStore;

final class CorrelatingCommandBus implements CommandBus
{
    private CommandBus $commandBus;
    private CorrelatingEventStore $eventStore;
    private CommandPublisher $commandPublisher;

    public function __construct(CommandBus $commandBus, CorrelatingEventStore $eventStore, CommandPublisher $commandPublisher)
    {
        $this->commandBus = $commandBus;
        $this->eventStore = $eventStore;
        $this->commandPublisher = $commandPublisher;
    }

    public function execute(Command $command): void
    {
        $this->eventStore->useCorrelationId($command->getId());
        $this->eventStore->useCausationId($command->getId());

        $this->commandBus->execute($command);
    }

    public function dispatch(): void
    {
        while ($this->commandPublisher->count() > 0) {
            $command = $this->commandPublisher->dequeue();
            $this->execute($command);
        }
    }
}
