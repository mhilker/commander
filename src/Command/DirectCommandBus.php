<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\Command\Exception\CommandHandlerNotFoundException;

final class DirectCommandBus implements CommandBus
{
    private CommandHandlers $handlers;

    public function __construct(CommandHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
    public function execute(Command $command): void
    {
        $commandClass = get_class($command);
        $handler = $this->handlers->getHandlerForCommand($commandClass);
        $handler($command);
    }

    public function dispatch(): void
    {
    }
}
