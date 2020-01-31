<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\Exception\UserNotSavedException;
use Commander\Stub\Aggregate\UserAggregate;
use Commander\Stub\Aggregate\UserRepository;

class RegisterUserCommandHandler
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
        $id = $command->getId();
        $name = $command->getName();

        $user = UserAggregate::register($id, $name);

        $this->repository->save($user);
    }
}
