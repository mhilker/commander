<?php

declare(strict_types=1);

namespace Commander\Event;

interface EventHandler
{
    public function handle(Events $events): void;
}
