<?php

declare(strict_types=1);

namespace Commander\Stub\EventStream;

use Commander\EventStream\EventStreamRepository;
use Commander\Stub\EventStream\Exception\UserNotFoundException;
use Commander\Stub\EventStream\Exception\UserNotSavedException;
use Exception;

final class EventStreamUserRepository implements UserRepository
{
    private EventStreamRepository $repository;

    public function __construct(EventStreamRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotSavedException
     */
    public function save(UserEventStream $user): void
    {
        try {
            $this->repository->save($user);
        } catch (Exception $exception) {
            throw new UserNotSavedException('User not saved', 0, $exception);
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function load(UserId $id): UserEventStream
    {
        try {
            return $this->repository->load($id);
        } catch (Exception $exception) {
            throw new UserNotFoundException('User not found', 0, $exception);
        }
    }
}
