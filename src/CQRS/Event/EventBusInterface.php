<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

use MHilker\EventSourcing\Event;

interface EventBusInterface
{
    public function trigger(Event $event): void;
}
