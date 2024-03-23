<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class CharacterOM
{
    private SharedUlid $ulid;
    private string $name;
    private int $baseExperience;

    public static function aBuilder(): static
    {
        return new static();
    }

    private function __construct()
    {
        $this->ulid = SharedUlid::fromEmpty();
        $this->name = 'Chindas';
        $this->baseExperience = 0;
    }

    public function withExperience(int $xpPoints): self
    {
        $this->baseExperience = $xpPoints;
        return $this;
    }

    public function build(): Character
    {
        $events = [
            new CharacterWasCreated(
                id: $this->ulid->ulid(),
                name: $this->name,
                experiencePoints: $this->baseExperience
            )
        ];
        $eventCollection = EventCollection::fromValues($this->ulid->ulid(), $events);
        return BasicCharacter::fromEvents($eventCollection);
    }
}
