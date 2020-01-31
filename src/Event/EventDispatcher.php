<?php

declare(strict_types=1);

namespace Commander\Event;

interface EventDispatcher
{
    public function dispatch(): void;
}
