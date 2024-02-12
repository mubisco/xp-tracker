<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class LevelWasIncreased implements DomainEvent
{
    private readonly DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $id,
        public readonly int $currentLevel
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function id(): string
    {
        return $this->id;
    }
}
