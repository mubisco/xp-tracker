<?php

declare(strict_types=1);

namespace XpTracker\Shared\Domain\Identity;

use InvalidArgumentException;
use Symfony\Component\Uid\Ulid;

final class SharedUlid
{
    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function fromEmpty(): static
    {
        $ulid = new Ulid();
        return new static($ulid->__toString());
    }

    private function __construct(private readonly string $ulid)
    {
        if (!Ulid::isValid($this->ulid)) {
            throw new WrongUlidValueException("{$ulid} is not a valid ULID");
        }
    }

    public function ulid(): string
    {
        return $this->ulid;
    }
}
