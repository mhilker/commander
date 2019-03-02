<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\Command\Exception\CommandHandlerNotFoundException;
use Commander\Command\Exception\InvalidCommandClassException;

final class CommandHandlers
{
    private $handlers = [];

    public function __construct(array $handlers)
    {
        foreach ($handlers as $commandClass => $commandHandler) {
            $this->add($commandClass, $commandHandler);
        }
    }

    public function add(string $commandClass, callable $commandHandler): void
    {
        if (class_exists($commandClass) === false) {
            throw new InvalidCommandClassException('');
        }

        $this->handlers[$commandClass] = $commandHandler;
    }

    public function has(string $commandClass): bool
    {
        return isset($this->handlers[$commandClass]) === true;
    }

    public function getHandlerForCommand(string $commandClass): callable
    {
        if ($this->has($commandClass) === false) {
            throw new CommandHandlerNotFoundException('');
        }

        return $this->handlers[$commandClass];
    }
}
