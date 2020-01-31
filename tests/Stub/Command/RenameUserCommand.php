<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;

class RenameUserCommand
{
    private UserId $id;

    private UserName $newName;

    public function __construct(UserId $id, UserName $newName)
    {
        $this->id = $id;
        $this->newName = $newName;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getNewName(): UserName
    {
        return $this->newName;
    }
}
