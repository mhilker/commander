<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Aggregate\AggregateRepository;
use Commander\Stub\Aggregate\Exception\UserNotFoundException;
use Commander\Stub\Aggregate\Exception\UserNotSavedException;
use Exception;

class AggregateUserRepository implements UserRepository
{
    private AggregateRepository $repository;

    public function __construct(AggregateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotSavedException
     */
    public function save(UserAggregate $user): void
    {
        try {
            $this->repository->save($user);
        } catch (Exception $exception) {
            throw new UserNotSavedException('User not saved.', 0, $exception);
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function load(UserId $id): UserAggregate
    {
        try {
            return $this->repository->load($id);
        } catch (Exception $exception) {
            throw new UserNotFoundException('User not found.', 0, $exception);
        }
    }
}
