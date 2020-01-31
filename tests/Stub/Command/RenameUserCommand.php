<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\UserId;

class RenameUserCommand
{
    private UserId $id;

    private string $name;

    public function __construct(UserId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
