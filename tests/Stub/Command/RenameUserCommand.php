<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Command\Command;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use Commander\UUID;
use Commander\UUIDImpl;

class RenameUserCommand implements Command
{
    private UUID $id;

    private UserId $userId;

    private UserName $name;

    public function __construct(UserId $userId, UserName $name)
    {
        $this->id = new UUIDImpl();
        $this->userId = $userId;
        $this->name = $name;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getId(): UUID
    {
        return $this->id;
    }
}
