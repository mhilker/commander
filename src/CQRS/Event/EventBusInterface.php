<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

use MHilker\EventSourcing\Event\Event;

interface EventBusInterface
{
    /**
     * @param Event $event
     * @return void
     */
    public function trigger(Event $event): void;
}
