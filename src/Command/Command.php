<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\ID\Identifier;

interface Command
{
    public function getId(): Identifier;
}
