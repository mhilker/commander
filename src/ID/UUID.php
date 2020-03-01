<?php

declare(strict_types=1);

namespace Commander\ID;

class UUID implements Identifier
{
    private const PATTERN_V4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    private string $id;

    /**
     * @throws InvalidUUIDException
     */
    private function __construct(string $id)
    {
        if ($id === '') {
            throw new InvalidUUIDException('ID must not be empty');
        }

        $this->id = $id;
    }

    /**
     * @throws InvalidUUIDException
     */
    public static function fromV4(string $id): self
    {
        if (!preg_match(self::PATTERN_V4, $id)) {
            throw new InvalidUUIDException('ID is invalid');
        }

        return new static($id);
    }

    /**
     * @throws InvalidUUIDException
     */
    public static function generateV4(): self
    {
        return new static(self::v4());
    }

    private static function v4(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public function asString(): string
    {
        return $this->id;
    }
}
