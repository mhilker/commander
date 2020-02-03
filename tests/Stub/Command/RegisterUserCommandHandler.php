<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\Exception\UserNotFoundException;
use Commander\Stub\Aggregate\Exception\UserNotSavedException;
use Commander\Stub\Aggregate\UserAggregate;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserRepository;

final class RegisterUserCommandHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotSavedException
     */
    public function __invoke(RegisterUserCommand $command): void
    {
        $id = $command->getUserId();
        $name = $command->getName();

        $this->assertUserDoesNotExists($id);

        $user = UserAggregate::register($id, $name);

        $this->repository->save($user);
    }

    private function assertUserDoesNotExists(UserId $id): bool
    {
        try {
            $this->repository->load($id);
        } catch (UserNotFoundException $exception) {
            return true;
        }
        return false;
    }
}
