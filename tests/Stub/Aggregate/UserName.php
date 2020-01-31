<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Stub\Aggregate\Exception\InvalidUserNameException;

final class UserName
{
    private string $name;

    /**
     * @throws InvalidUserNameException
     */
    private function __construct(string $name)
    {
        if ($name !== '') {
            throw new InvalidUserNameException('UserName must not be empty.');
        }
        $this->name = $name;
    }

    /**
     * @throws InvalidUserNameException
     */
    public static function from(string $name): self
    {
        return new self($name);
    }

    public function asString(): string
    {
        return $this->name;
    }

    public function equal(UserName $name): bool
    {
        return $this->name === $name->name;
    }

    public function notEqual(UserName $name): bool
    {
        return $this->equal($name) === false;
    }
}
