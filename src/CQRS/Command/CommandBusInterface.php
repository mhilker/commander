<?php

declare(strict_types=1);

namespace MHilker\CQRS\Command;

interface CommandBusInterface
{
    public function execute($command): void;
}
