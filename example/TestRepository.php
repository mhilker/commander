<?php

declare(strict_types=1);

namespace MHilker\Example;

use MHilker\EventSourcing\Repository\AggregateRepositoryInterface;

class TestRepository
{
    private $repository;

    public function __construct(AggregateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function save(TestAggregate $test): void
    {
        $this->repository->save($test);
    }

    public function load(TestId $id): TestAggregate
    {
        return $this->repository->load($id);
    }
}
