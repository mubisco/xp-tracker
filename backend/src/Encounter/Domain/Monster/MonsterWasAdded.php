<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Monster;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class MonsterWasAdded implements DomainEvent
{
    private readonly DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $encounterUlid,
        public readonly string $monsterName,
        public readonly string $challengeRating,
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->encounterUlid;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
