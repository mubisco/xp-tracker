<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use InvalidArgumentException;

final class CharacterName
{
    public static function fromString(string $value): static
    {
        return new self($value);
    }

    private function __construct(private readonly string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException();
        }
    }

    public function name(): string
    {
        return $this->name;
    }
}
