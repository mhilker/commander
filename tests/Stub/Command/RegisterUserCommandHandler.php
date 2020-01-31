<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\Exception\UserNotSavedException;
use Commander\Stub\Aggregate\UserAggregate;
use Commander\Stub\Aggregate\UserRepository;
use Commander\Stub\Command\Exception\UserAlreadyExistsException;

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
        $id = $command->getUserId();
        $name = $command->getName();

        // TODO
//        $user = $this->repository->load($id);
//        if ($user) {
//            throw new UserAlreadyExistsException('User already exists.');
//        }

        $user = UserAggregate::register($id, $name);

        $this->repository->save($user);
    }
}
