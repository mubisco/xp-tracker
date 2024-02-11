<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use DomainException;
use XpTracker\Shared\Domain\Event\DomainEvent;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class BasicCharacter implements Character
{
    private readonly SharedUlid $ulid;
    private CharacterName $characterName;
    private Experience $experience;

    /** @var array<int, DomainEvent> $events */
    private array $events = [];

    public static function create(string $ulid, string $name, int $experiencePoints): static
    {
        $character = new self($ulid);
        $createEvent = new CharacterWasCreated($ulid, $name, $experiencePoints);
        $character->apply($createEvent);
        return $character;
    }

    /** @param array<int, DomainEvent> $events */
    public static function fromEvents(SharedUlid $ulid, array $events): BasicCharacter
    {
        $character = new self($ulid->ulid());
        foreach ($events as $event) {
            $character->apply($event);
        }
        return $character;
    }

    private function __construct(string $ulid)
    {
        $this->ulid = SharedUlid::fromString($ulid);
    }

    public function id(): string
    {
        return $this->ulid->ulid();
    }

    public function toJson(): string
    {
        $data = [
            'name' => $this->characterName->name(),
            'xp' => $this->experience->points(),
            'level' => $this->experience->level(),
            'next' => $this->experience->nextLevel(),
        ];
        $parsedData = json_encode($data);
        return $parsedData ? $parsedData : '';
    }

    private function apply(DomainEvent $event): void
    {
        $this->validateEvent($event);
        $reflect = new \ReflectionClass($event);
        $method = "apply{$reflect->getShortName()}";
        $this->$method($event);
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

    private function applyCharacterWasCreated(CharacterWasCreated $event): void
    {
        $this->characterName = CharacterName::fromString($event->name);
        $this->experience = Experience::fromInt($event->experiencePoints);
        $this->events[] = $event;
    }

    private function applyExperienceWasAdded(ExperienceWasAdded $event): void
    {
        $total = $this->experience->points() + $event->points;
        $this->experience = Experience::fromInt($total);
        $this->events[] = $event;

    }

    public function pullEvents(): array
    {
        return $this->events;
    }
}
