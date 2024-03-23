<?php

declare(strict_types=1);

namespace XpTracker\Shared\Domain\Event;

use DomainException;
use InvalidArgumentException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EventCollection
{
    private readonly SharedUlid $ulid;
    /** @var array<int, DomainEvent> $events */
    private readonly array $events;

    /**
     * @param array<int,DomainEvent> $events
     */
    public static function fromValues(string $ulid, array $events): static
    {
        return new static($ulid, $events);
    }
    /**
     * @param array<int,DomainEvent> $events
     */
    private function __construct(string $ulid, array $events)
    {
        try {
            $this->ulid = SharedUlid::fromString($ulid);
            $this->validateEvents($events);
            $this->events = $events;

        } catch (DomainException) {
            throw new EmptyEventsForCollectionException("Aggregate {$ulid} has no events attached!!!");
        }
    }

    public function ulid(): string
    {
        return $this->ulid->ulid();
    }

    /** @return array<int, DomainEvent> */
    public function events(): array
    {
        return $this->events;
    }
    /**
     * @param array<int,mixed> $events
     */
    private function validateEvents(array $events): void
    {
        if (empty($events)) {
            throw new \DomainException();
        }
        foreach ($events as $event) {
            if (!is_a($event, DomainEvent::class)) {
                throw new InvalidArgumentException('Only DomainEvents allowed!!!');
            }
        }
    }
}
