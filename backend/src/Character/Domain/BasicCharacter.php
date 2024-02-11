<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use DomainException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class BasicCharacter implements Character
{
    private readonly SharedUlid $ulid;
    private CharacterName $characterName;
    private Experience $experience;

    private array $events = [];

    public static function create(string $ulid, string $name, int $experiencePoints): static
    {
        $character = new self($ulid);
        $createEvent = new CharacterWasCreated($ulid, $name, $experiencePoints);
        $character->apply($createEvent);
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
        return json_encode($data);
    }

    private function apply(CharacterWasCreated $event): void
    {
        if ($event->id !== $this->id()) {
            throw new DomainException(sprintf(
                'Event id (%s) and Aggregate Id (%s) mismatch',
                $event->id,
                $this->id()
            ));
        }
        $this->characterName = CharacterName::fromString($event->name);
        $this->experience = Experience::fromInt($event->experiencePoints);
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        return $this->events;
    }
}
