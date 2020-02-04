<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\Command\Exception\CommandFailedException;
use Commander\Command\Exception\CommandHandlerNotFoundException;
use Commander\Event\EventDispatcher;
use Commander\EventStore\CorrelatingEventStore;
use Exception;

final class DirectCommandBus implements CommandBus
{
    private CommandHandlers $commandHandlers;
    private CorrelatingEventStore $eventStore;
    private CommandPublisher $commandPublisher;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        CommandHandlers $commandHandlers,
        CorrelatingEventStore $eventStore,
        CommandPublisher $commandPublisher,
        EventDispatcher $eventDispatcher
    ) {
        $this->commandHandlers = $commandHandlers;
        $this->eventStore = $eventStore;
        $this->commandPublisher = $commandPublisher;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws CommandFailedException
     */
    public function execute(Command $command): void
    {
        $this->commandPublisher->publish($command);

        while ($this->commandPublisher->count() > 0) {
            $command = $this->commandPublisher->dequeue();
            try {
                $this->doExecute($command);
            } catch (Exception $exception) {
                throw new CommandFailedException('Command failed.', 0, $exception);
            }
        }
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
    private function doExecute(Command $command): void
    {
        $this->eventStore->useCorrelationId($command->getId());
        $this->eventStore->useCausationId($command->getId());

        $commandClass = get_class($command);
        $handler = $this->commandHandlers->getHandlerForCommand($commandClass);
        $handler($command);

        $this->eventDispatcher->dispatch();
    }
}


