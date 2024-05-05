<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain\Party;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterNotInPartyException;
use XpTracker\Shared\Domain\AggregateRoot;

final class BasicParty extends AggregateRoot implements Party
{
    private string $name;
    /** @var array<int,string> $characters */
    private array $characters;

    public static function create(string $id, string $name): static
    {
        $instance = new static($id);
        $event = new PartyWasCreated($id, $name);
        $instance->apply($event);
        return $instance;
    }

    protected function collect(): array
    {
        return ['name' => $this->name, 'characters' => $this->characters];
    }

    protected function applyPartyWasCreated(PartyWasCreated $event): void
    {
        $this->name = $event->partyName;
        $this->characters = [];
    }

    protected function applyCharacterWasAdded(CharacterWasAdded $event): void
    {
        $this->characters[] = $event->addedCharacterId;
    }

    protected function applyPartyCharacterWasRemoved(PartyCharacterWasRemoved $event): void
    {
        $updatedCharacters = array_filter($this->characters, function (string $characterId) use ($event) {
            return $characterId != $event->characterId;
        });
        if (count($updatedCharacters) === count($this->characters)) {
            throw new CharacterNotInPartyException();
        }
        $this->characters = $updatedCharacters;
    }

    public function add(Character $character): void
    {
        $character->join($this);
        $this->apply(new CharacterWasAdded($this->id(), $character->id()));
    }

    public function characterCount(): int
    {
        return count($this->characters);
    }

    public function remove(Character $character): void
    {
        $event = new PartyCharacterWasRemoved(partyId: $this->id(), characterId: $character->id());
        $this->apply($event);
    }
}
