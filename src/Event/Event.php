<?php

declare(strict_types=1);

namespace Commander\Event;

interface Event
{
    public function getType(): string;
}
