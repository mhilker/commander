<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\Command\Exception\CommandHandlerNotFoundException;
use Commander\Command\Exception\InvalidCommandClassException;

final class CommandHandlers
{
    private array $handlers = [];

    public function __construct(array $handlers)
    {
        foreach ($handlers as $commandClass => $commandHandler) {
            $this->add($commandClass, $commandHandler);
        }
    }

    public function add(string $commandClass, callable $commandHandler): void
    {
        if (!class_exists($commandClass)) {
            throw new InvalidCommandClassException('Command does not exists.');
        }

        $this->handlers[$commandClass] = $commandHandler;
    }

    public function has(string $commandClass): bool
    {
        return isset($this->handlers[$commandClass]);
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
    public function getHandlerForCommand(string $commandClass): callable
    {
        if (!$this->has($commandClass)) {
            throw new CommandHandlerNotFoundException('Could not find handler for command');
        }

        return $this->handlers[$commandClass];
    }
}
