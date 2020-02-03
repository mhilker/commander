<?php

declare(strict_types=1);

namespace Commander;

interface Identifier
{
    public function asString(): string;
}
