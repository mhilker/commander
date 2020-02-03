<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Event\Event;
use Commander\Stub\Aggregate\UserId;

final class UserDisabledEvent implements Event
{
    public const TOPIC = 'com.example.event.user_disabled';

    private UserId $id;

    private function __construct(UserId $id)
    {
        $this->id = $id;
    }

    public static function occur(UserId $id): self
    {
        return new self($id);
    }

    public static function restore(array $payload): Event
    {
        $userId = UserId::from($payload['id']);

        return new self($userId);
    }

    public function getPayload(): array
    {
        return [
            'id' => $this->id->asString(),
        ];
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getVersion(): int
    {
        return 1;
    }
}
