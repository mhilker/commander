<?php

declare(strict_types=1);

namespace Commander\Unit\EventStream;

use Commander\Event\Event;
use Commander\ID\Identifier;
use Commander\ID\UUID;

final class StubEvent implements Event
{
    private Identifier $id;

    public function __construct(Identifier $id)
    {
        $this->id = $id;
    }

    public static function fromPayload(array $payload): Event
    {
        $id = UUID::fromV4($payload['id']);

        return new self($id);
    }

    public function getPayload(): array
    {
        return [
            'id' => $this->id->asString(),
        ];
    }

    public function getId(): Identifier
    {
        return $this->id;
    }

    public function getTopic(): string
    {
        return 'topic';
    }

    public function getVersion(): int
    {
        return 1;
    }
}
