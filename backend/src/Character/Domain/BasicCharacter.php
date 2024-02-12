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
            $character->applyWithoutStore($event);
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

    private function applyWithoutStore(DomainEvent $event): void
    {
        $this->validateEvent($event);
        $reflect = new \ReflectionClass($event);
        $method = "apply{$reflect->getShortName()}";
        $this->$method($event);
    }

    private function apply(DomainEvent $event): void
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

    private function applyCharacterWasCreated(CharacterWasCreated $event): void
    {
        $this->characterName = CharacterName::fromString($event->name);
        $this->experience = Experience::fromInt($event->experiencePoints);
    }

    private function applyExperienceWasUpdated(ExperienceWasUpdated $event): void
    {
        $anotherExperience = Experience::fromInt($event->points);
        $this->experience = $this->experience->add($anotherExperience);
    }

    private function applyLevelWasIncreased(LevelWasIncreased $event): void
    {
    }

    public function pullEvents(): array
    {
        return $this->events;
    }

    public function addExperience(Experience $experience): void
    {
        $previousLevel = $this->experience->level();
        $event = new ExperienceWasUpdated(id: $this->id(), points: $experience->points());
        $this->apply($event);
        if ($this->experience->level() > $previousLevel) {
            $this->events[] = new LevelWasIncreased($this->id(), $this->experience->level());
        }
    }
}
