<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Command\Command;
use Commander\IdentifierTrait;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;

final class RegisterUserCommand implements Command
{
    use IdentifierTrait;

    private UserId $userId;
    private UserName $name;

    public function __construct(UserId $userId, UserName $name)
    {
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
}
