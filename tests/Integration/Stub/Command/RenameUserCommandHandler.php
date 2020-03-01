<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\Command;

use Commander\Integration\Stub\EventStream\Exception\UserNotFoundException;
use Commander\Integration\Stub\EventStream\Exception\UserNotSavedException;
use Commander\Integration\Stub\EventStream\UserRepository;

final class RenameUserCommandHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserNotSavedException
     */
    public function __invoke(RenameUserCommand $command): void
    {
        $id = $command->getUserId();
        $name = $command->getName();

        $user = $this->repository->load($id);
        $user->rename($name);

        $this->repository->save($user);
    }
}
