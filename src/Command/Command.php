<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\Identifier;

interface Command
{
    public function getId(): Identifier;
}
