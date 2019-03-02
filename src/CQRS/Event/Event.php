<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

interface Event
{
    public function getType(): string;
}