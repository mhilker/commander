<?php

declare(strict_types=1);

namespace MHilker\CQRS\Command;

use MHilker\CQRS\Command\Exception\InvalidCommandException;

class DirectCommandBus implements CommandBusInterface
{
    private $handlers;

    public function __construct(CommandHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    public function execute($command): void
    {
        if (is_object($command) === false) {
            throw new InvalidCommandException();
        }

        $commandClass = get_class($command);

        $handler = $this->handlers->getHandlerForCommand($commandClass);
        $handler($command);
    }
}
