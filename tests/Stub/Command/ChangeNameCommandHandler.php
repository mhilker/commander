<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Command\ChangeNameCommand;
use Commander\Stub\Aggregate\TestRepository;

class ChangeNameCommandHandler
{
    private $repository;

    public function __construct(TestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ChangeNameCommand $command): void
    {
        $id = $command->getId();
        $name = $command->getName();

        $test = $this->repository->load($id);
        $test->changeName($name);

        $this->repository->save($test);
    }
}
