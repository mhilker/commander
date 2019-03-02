<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

interface EventHandler
{
    public function handle(Events $events): void;
}
