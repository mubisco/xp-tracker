<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Domain;

use DomainException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class BasicCharacter implements Character
{
    private readonly SharedUlid $ulid;
    private array $events = [];

    public static function create(string $ulid): static
    {
        return new self($ulid);
    }

    private function __construct(string $ulid)
    {
        $this->ulid = SharedUlid::fromString($ulid);
    }

    public function id(): string
    {
        return $this->ulid->ulid();
    }

    public function applyEvent(CharacterWasCreated $event): void
    {
        if ($event->id !== $this->id()) {
            throw new DomainException(sprintf(
                'Event id (%s) and Aggregate Id (%s) mismatch',
                $event->id,
                $this->id()
            ));
        }
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        return $this->events;
    }
}
