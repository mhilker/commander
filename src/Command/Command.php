<?php

declare(strict_types=1);

namespace Commander\Command;

use Commander\UUID;

interface Command
{
    public function getId(): UUID;

    // TODO: Topic?
}
