<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\TestId;

class ChangeNameCommand
{
    private TestId $id;

    private string $name;

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
