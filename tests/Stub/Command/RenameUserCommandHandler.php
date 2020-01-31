<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\UserRepository;

class RenameUserCommandHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RenameUserCommand $command): void
    {
        $id = $command->getId();
        $name = $command->getNewName();

        $user = $this->repository->load($id);
        $user->rename($name);

        $this->repository->save($user);
    }
}
