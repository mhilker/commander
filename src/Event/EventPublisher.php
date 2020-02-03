<?php

declare(strict_types=1);

namespace Commander\Event;

interface EventPublisher
{
    public function publish(Messages $messages): void;
}
