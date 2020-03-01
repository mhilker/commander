<?php

declare(strict_types=1);

namespace Commander\Stub\EventStream;

use Commander\Stub\EventStream\Exception\UserNotFoundException;
use Commander\Stub\EventStream\Exception\UserNotSavedException;

interface UserRepository
{
    /**
     * @throws UserNotSavedException
     */
    public function save(UserEventStream $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function load(UserId $id): UserEventStream;
}
