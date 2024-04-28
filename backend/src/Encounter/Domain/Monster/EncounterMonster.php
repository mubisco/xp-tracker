<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Monster;

use InvalidArgumentException;

final class EncounterMonster
{
    private readonly ChallengeRating $challengeRating;

    public static function fromStringValues(string $name, string $challengeRating): static
    {
        return new static($name, $challengeRating);
    }

    private function __construct(
        private readonly string $name,
        string $challengeRating
    ) {
        try {
            if (empty($this->name)) {
                throw new InvalidArgumentException("Monster name cannot be empty!!!");
            }
            $this->challengeRating = ChallengeRating::fromString($challengeRating);
        } catch (InvalidArgumentException $e) {
            throw new WrongMonsterValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function challengeRating(): string
    {
        return $this->challengeRating->rating();
    }

    public function xp(): int
    {
        return $this->challengeRating->xp();
    }
}
