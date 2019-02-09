<?php

declare(strict_types=1);

namespace MHilker\EventSourcing;

use DateTimeImmutable;

interface Event
{
    public static function restore(array $data): Event;

    public function getOccurredOn(): DateTimeImmutable;

    public function getName(): string;

    public function getPayload(): array;
}
