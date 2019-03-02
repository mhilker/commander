<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

interface EventBus
{
    public function dispatch(Events $events): void;
}
