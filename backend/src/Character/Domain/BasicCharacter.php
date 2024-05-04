<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Shared\Domain\AggregateRoot;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class BasicCharacter extends AggregateRoot implements Character
{
    private CharacterName $characterName;
    private Experience $experience;
    private ?SharedUlid $partyId;

    public static function create(string $ulid, string $name, int $experiencePoints): static
    {
        $character = new self($ulid);
        $createEvent = new CharacterWasCreated($ulid, $name, $experiencePoints);
        $character->apply($createEvent);
        return $character;
    }

    public function level(): int
    {
        return $this->experience->level();
    }

    protected function collect(): array
    {
        return [
            'name' => $this->characterName->name(),
            'xp' => $this->experience->points(),
            'level' => $this->experience->level(),
            'next' => $this->experience->nextLevel(),
            'partyId' => $this->partyId?->ulid() ?? ''
        ];
    }

    protected function applyCharacterWasCreated(CharacterWasCreated $event): void
    {
        $this->characterName = CharacterName::fromString($event->name);
        $this->experience = Experience::fromInt($event->experiencePoints);
        $this->partyId = null;
    }

    protected function applyExperienceWasUpdated(ExperienceWasUpdated $event): void
    {
        $anotherExperience = Experience::fromInt($event->points);
        $this->experience = $this->experience->add($anotherExperience);
    }

    protected function applyCharacterJoined(CharacterJoined $characterJoined): void
    {
        if (null !== $this->partyId) {
            throw new CharacterAlreadyInPartyException();
        }
        $this->partyId = SharedUlid::fromString($characterJoined->partyId);
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

    public function join(Party $party): void
    {
        $this->apply(new CharacterJoined($this->id(), $party->id()));
    }
}
