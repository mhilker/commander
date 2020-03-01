<?php

declare(strict_types=1);

namespace Commander\ID;

trait HasIdentifier
{
    private ?UUID $id = null;

    public function getId(): Identifier
    {
        if ($this->id === null) {
            $this->id = UUID::generateV4();
        }

        return $this->id;
    }
}
