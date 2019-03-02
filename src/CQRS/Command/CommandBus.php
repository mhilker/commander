<?php

declare(strict_types=1);

namespace MHilker\CQRS\Command;

interface CommandBus
{
    /**
     * @param object $command
     * @return void
     */
    public function execute($command): void;
}
