<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;

class RegisterUserCommand
{
    private UserId $id;

    private UserName $name;

    public function __construct(UserId $id, UserName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): UserName
    {
        return $this->name;
    }
}
