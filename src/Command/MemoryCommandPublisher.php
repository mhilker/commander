<?php

declare(strict_types=1);

namespace Commander\Command;

use SplQueue;

final class MemoryCommandPublisher implements CommandPublisher
{
    private SplQueue $commands;

    public function __construct()
    {
        $this->commands = new SplQueue();
    }

    public function publish(Command $command): void
    {
        $this->commands->enqueue($command);
    }

    public function isEmpty(): bool
    {
        return $this->commands->isEmpty();
    }

    public function dequeue(): Command
    {
        return $this->commands->dequeue();
    }
}
