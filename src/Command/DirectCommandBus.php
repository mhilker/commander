<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\Command\Exception\CommandHandlerNotFoundException;
use Commander\Command\Exception\InvalidCommandException;

final class DirectCommandBus implements CommandBus
{
    /** @var CommandHandlers */
    private $handlers;

    public function __construct(CommandHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
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
