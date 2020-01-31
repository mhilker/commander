<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

interface UserRepository
{
    public function save(UserAggregate $user): void;

    public function load(UserId $id): UserAggregate;
}
