<?php

declare(strict_types=1);

namespace Commander\ID;

interface Identifier
{
    public function asString(): string;
}
