<?php

declare(strict_types=1);

namespace Commander\Util;

interface Identifier
{
    public function asString(): string;
}
