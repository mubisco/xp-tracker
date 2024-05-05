<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\CharacterWasAdded;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class PartyOM
{
    private SharedUlid $ulid;
    private string $name;
    private array $characters;

    public static function aBuilder(): static
    {
        return new static();
    }

    private function __construct()
    {
        $this->ulid = SharedUlid::fromEmpty();
        $this->name = 'Comando G';
        $this->characters = [];
    }

    public function withUlid(string $ulid): static
    {
        $this->ulid = SharedUlid::fromString($ulid);
        return $this;
    }

    public function withCharacter(Character $character): static
    {
        $this->characters[] = $character;
        return $this;
    }

    public function build(): Party
    {
        $events = [new PartyWasCreated($this->ulid->ulid(), $this->name)];
        $characterEvents = $this->parseCharacters();
        $mergedEvents = array_merge($events, $characterEvents);
        $collection = EventCollection::fromValues($this->ulid->ulid(), $mergedEvents);
        return BasicParty::fromEvents($collection);
    }

    private function parseCharacters(): array
    {
        $events = [];
        foreach ($this->characters as $character) {
            $events[] = new CharacterWasAdded(id: $this->ulid->ulid(), addedCharacterId: $character->id());
        }
        return $events;

    }
}
