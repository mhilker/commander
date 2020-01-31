<?php

declare(strict_types=1);

namespace Commander;

// TODO: fix this shit
final class UUIDImpl implements UUID
{
    private string $value;

    public function __construct(string $value = null)
    {
        if ($value === null) {
            $value = trim(shell_exec('uuid -v4'));
        }
        $this->value = $value;
    }

    public function asString(): string
    {
        return $this->value;
    }
}
