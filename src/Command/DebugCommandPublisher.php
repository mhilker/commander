<?php

declare(strict_types=1);

namespace Commander\Command;

use SplQueue;

final class DebugCommandPublisher implements CommandPublisher
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

    public function count(): int
    {
        return $this->commands->count();
    }

    public function dequeue(): Command
    {
        return $this->commands->dequeue();
    }
}
