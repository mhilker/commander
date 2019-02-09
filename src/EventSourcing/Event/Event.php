<?php

declare(strict_types=1);

namespace MHilker\EventSourcing\Event;

use DateTimeImmutable;

interface Event
{
    /**
     * @param array $data
     * @return Event
     */
    public static function restore(array $data): Event;

    /**
     * @return DateTimeImmutable
     */
    public function getOccurredOn(): DateTimeImmutable;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getPayload(): array;
}
