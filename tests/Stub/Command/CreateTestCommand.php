<?php

declare(strict_types=1);

namespace Commander\Stub;

class CreateTestCommand
{
    private $id;

    private $name;

    public function __construct(TestId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): TestId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
