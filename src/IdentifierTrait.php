<?php

declare(strict_types=1);

namespace Commander;

trait IdentifierTrait
{
    private ?UUID $id = null;

    public function getId(): Identifier
    {
        if ($this->id === null) {
            $this->id = UUID::generate();
        }

        return $this->id;
    }
}
