<?php

declare(strict_types=1);

namespace Commander\Command;

interface CommandPublisher
{
    public function publish(Command $command): void;

    public function count(): int;

    public function dequeue(): Command;
}
