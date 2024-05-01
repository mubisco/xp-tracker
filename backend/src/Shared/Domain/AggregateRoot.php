<?php

declare(strict_types=1);

namespace XpTracker\Shared\Domain;

use DomainException;
use XpTracker\Shared\Domain\Event\DomainEvent;
use XpTracker\Shared\Domain\Event\Eventable;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

abstract class AggregateRoot implements Eventable
{
    private readonly SharedUlid $ulid;
    /** @var array<int, DomainEvent> $events */
    private array $events = [];

    public static function fromEvents(EventCollection $eventCollection): static
    {
        $instance = new static($eventCollection->ulid());
        foreach ($eventCollection->events() as $event) {
            $instance->applyWithoutStore($event);
        }
        return $instance;
    }

    final public function __construct(string $id)
    {
        $this->ulid = SharedUlid::fromString($id);
    }

    protected function applyWithoutStore(DomainEvent $event): void
    {
        $this->validateEvent($event);
        $reflect = new \ReflectionClass($event);
        $method = "apply{$reflect->getShortName()}";
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }

    protected function apply(DomainEvent $event): void
    {
        $this->events[] = $event;
        $this->applyWithoutStore($event);
    }

    private function validateEvent(DomainEvent $event): void
    {
        if ($event->id() !== $this->id()) {
            throw new DomainException(sprintf(
                'Event id (%s) and Aggregate Id (%s) mismatch',
                $event->id(),
                $this->id()
            ));
        }
    }

    public function pullEvents(): array
    {
        return $this->events;
    }

    public function id(): string
    {
        return $this->ulid->ulid();
    }
    /**
     * @return array<string,mixed>
     */
    abstract protected function collect(): array;

    public function toJson(): string
    {
        $data = $this->collect();
        $parsedData = json_encode($data);
        return $parsedData ? $parsedData : '';
    }
}
