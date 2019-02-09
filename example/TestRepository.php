<?php

declare(strict_types=1);

namespace MHilker\Example;

use MHilker\EventSourcing\AggregateId;
use MHilker\EventSourcing\AggregateRepository;

class TestRepository
{
    /** @var AggregateRepository */
    private $repository;

    public function __construct(AggregateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository(): AggregateRepository
    {
        return $this->repository;
    }

    public function save(TestAggregate $test): void
    {
        $this->repository->save($test);
    }

    public function load(AggregateId $id): TestAggregate
    {
        return $this->repository->load($id);
    }
}
