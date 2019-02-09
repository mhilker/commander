<?php

declare(strict_types=1);

namespace MHilker\Example;

class TestCommandHandler
{
    private $repository;

    public function __construct(TestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(TestCommand $command): void
    {
        $id = new TestId(UUID::v4());

        $entity = TestAggregate::create($id);

        $this->repository->save($entity);
    }
}
