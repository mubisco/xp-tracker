<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use XpTracker\Shared\Domain\AggregateRoot;
use XpTracker\Shared\Domain\Event\DomainEvent;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class BasicCharacter extends AggregateRoot implements Character
{
    private CharacterName $characterName;
    private Experience $experience;

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

    protected function collect(): array
    {
        return [
            'name' => $this->characterName->name(),
            'xp' => $this->experience->points(),
            'level' => $this->experience->level(),
            'next' => $this->experience->nextLevel(),
        ];
    }

    protected function applyCharacterWasCreated(CharacterWasCreated $event): void
    {
        $this->characterName = CharacterName::fromString($event->name);
        $this->experience = Experience::fromInt($event->experiencePoints);
    }

    protected function applyExperienceWasUpdated(ExperienceWasUpdated $event): void
    {
        $anotherExperience = Experience::fromInt($event->points);
        $this->experience = $this->experience->add($anotherExperience);
    }

    public function addExperience(Experience $experience): void
    {
        $previousLevel = $this->experience->level();
        $event = new ExperienceWasUpdated(id: $this->id(), points: $experience->points());
        $this->apply($event);
        if ($this->experience->level() > $previousLevel) {
            $this->apply(new LevelWasIncreased($this->id(), $this->experience->level()));
        }
    }
}
