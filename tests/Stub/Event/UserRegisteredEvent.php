<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Event\Event;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;

final class UserRegisteredEvent implements Event
{
    public const TOPIC = 'com.example.event.user_registered';

    private UserId $id;
    private UserName $name;

    private function __construct(UserId $id, UserName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function occur(UserId $id, UserName $name): self
    {
        return new self($id, $name);
    }

    public static function restore(array $payload): Event
    {
        $id = UserId::from($payload['id']);
        $name = UserName::from($payload['name']);

        return new self($id, $name);
    }

    public function getPayload(): array
    {
        return [
            'id'   => $this->id->asString(),
            'name' => $this->name->asString(),
        ];
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): UserName
    {
        return $this->name;
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
