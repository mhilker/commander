<?php

declare(strict_types=1);

namespace Commander\Command;

interface CommandBus
{
    /**
     * @param object $command
     * @return void
     */
    public function execute($command): void;
}
