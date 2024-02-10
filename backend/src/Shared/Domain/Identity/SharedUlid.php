<?php

declare(strict_types=1);

namespace XpTracker\Shared\Domain\Identity;

use InvalidArgumentException;
use Symfony\Component\Uid\Ulid;

final class SharedUlid
{
    public static function fromEmpty(): static
    {
        $ulid = new Ulid();
        return new static($ulid->__toString());
    }

    public function __construct(private readonly string $ulid)
    {
        if (!Ulid::isValid($this->ulid)) {
            throw new InvalidArgumentException("{$ulid} is not a valid ULID");
        }
    }

    public function ulid(): string
    {
        return $this->ulid;
    }
}
