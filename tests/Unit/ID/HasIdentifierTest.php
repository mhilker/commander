<?php

declare(strict_types=1);

namespace Commander\Unit\ID;

use Commander\ID\HasIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\ID\HasIdentifier
 */
final class HasIdentifierTest extends TestCase
{
    use HasIdentifier;

    public function testGeneratesOnlyOneId(): void
    {
        $this->assertEquals($this->getId(), $this->getId());
    }
}
