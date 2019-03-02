<?php

declare(strict_types=1);

namespace Commander\Event;

interface EventBus
{
    public function dispatch(Events $events): void;
}
