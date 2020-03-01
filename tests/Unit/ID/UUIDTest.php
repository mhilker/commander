<?php

declare(strict_types=1);

namespace Commander\Unit\ID;

use Commander\ID\InvalidUUIDException;
use Commander\ID\UUID;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\ID\UUID
 */
final class UUIDTest extends TestCase
{
    public function testCreatesIDFromV4(): void
    {
        $first = UUID::generateV4();
        $second = UUID::fromV4($first->asString());

        $this->assertEquals($first, $second);
    }

    public function testThrowsExceptionWhenIdIsEmpty(): void
    {
        $this->expectException(InvalidUUIDException::class);
        $this->expectExceptionMessage('ID is invalid');

        UUID::fromV4('');
    }
}
