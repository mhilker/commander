<?php

declare(strict_types=1);

namespace Commander\Stub;

class TestCommandHandler
{
    private $repository;

    public function __construct(TestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(TestCommand $command): void
    {
        $id = $command->getId();
        $name = $command->getName();

        $entity = TestAggregate::create($id, $name);

        $this->repository->save($entity);
    }
}
