<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Stub\Aggregate\Exception\UserNotFoundException;
use Commander\Stub\Aggregate\Exception\UserNotSavedException;

interface UserRepository
{
    /**
     * @throws UserNotSavedException
     */
    public function save(UserAggregate $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function load(UserId $id): UserAggregate;
}
