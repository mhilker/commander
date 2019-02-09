<?php

declare(strict_types=1);

namespace MHilker\CQRS\Command;

interface CommandBusInterface
{
    /**
     * @param object $command
     * @return void
     */
    public function execute($command): void;
}
