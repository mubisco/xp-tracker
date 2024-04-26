<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain;

final class EncounterName
{
    private const REGEX_NAME = '/^(\s|\d|\w)+$/';

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    private function __construct(private readonly string $value)
    {
        if (!preg_match(self::REGEX_NAME, $value)) {
            throw new WrongEncounterNameException("{$value} is not a valid Encounter Name");
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
