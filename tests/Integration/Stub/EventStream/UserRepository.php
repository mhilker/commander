<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\EventStream;

use Commander\Integration\Stub\EventStream\Exception\UserNotFoundException;
use Commander\Integration\Stub\EventStream\Exception\UserNotSavedException;

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
