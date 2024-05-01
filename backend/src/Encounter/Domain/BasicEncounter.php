<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain;

use XpTracker\Encounter\Domain\Level\EncounterLevel;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Monster\MonsterWasRemoved;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;
use XpTracker\Shared\Domain\AggregateRoot;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class BasicEncounter extends AggregateRoot implements Encounter
{
    private EncounterName $name;
    private ?SharedUlid $partyUlid = null;
    private EncounterLevel $level;
    /** @var array<int, EncounterMonster> */
    private array $monsters = [];

    public static function create(string $ulid, string $name): static
    {
        try {
            $encounter = new static($ulid);
            $event = new EncounterWasCreated($ulid, $name);
            $encounter->apply($event);
            return $encounter;
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterUlidException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function collect(): array
    {
        $monsters = $this->collectMonstersData();
        $party = $this->partyUlid?->ulid() ?? '';
        return [
            'name' => $this->name->value(),
            'party' => $party,
            'status' => $this->level->level()->value,
            'monsters' => $monsters
        ];
    }
    /**
     * @return array<int,array<string,mixed>>
     */
    public function collectMonstersData(): array
    {
        return array_map(function (EncounterMonster $monster) {
            return [
                'name' => $monster->name(),
                'challengeRating' => $monster->challengeRating(),
                'experiencePoints' => $monster->xp()
            ];
        }, $this->monsters);
    }

    protected function applyEncounterWasCreated(EncounterWasCreated $event): void
    {
        $this->name = EncounterName::fromString($event->name);
        $this->level = EncounterLevel::empty();
    }

    public function monsterQuantity(): int
    {
        return count($this->monsters);
    }

    protected function applyMonsterWasAdded(MonsterWasAdded $event): void
    {
        $monster = EncounterMonster::fromStringValues($event->monsterName, $event->challengeRating);
        $this->monsters[] = $monster;
    }
    protected function applyMonsterWasRemoved(MonsterWasRemoved $event): void
    {
        $monster = EncounterMonster::fromStringValues($event->monsterName, $event->challengeRating);
        $updatedMonsters = [];
        foreach ($this->monsters as $singleMonster) {
            if (!$singleMonster->equals($monster)) {
                $updatedMonsters[] = $singleMonster;
            }
        }
        $this->monsters = $updatedMonsters;
    }

    protected function applyPartyWasAssigned(PartyWasAssigned $event): void
    {
        $this->partyUlid = SharedUlid::fromString($event->partyUlid);
        $this->level = EncounterLevel::fromValues($event->charactersLevel, $this->monsterXpValues());
    }

    private function monsterXpValues(): array
    {
        return array_map(function (EncounterMonster $monster) {
            return $monster->xp();
        }, $this->monsters);
    }

    public function addMonster(EncounterMonster $monster): void
    {
        $event = new MonsterWasAdded(
            encounterUlid: $this->id(),
            monsterName: $monster->name(),
            challengeRating: $monster->challengeRating()
        );
        $this->apply($event);
    }

    public function removeMonster(EncounterMonster $monster): void
    {
        $event = new MonsterWasRemoved(
            encounterUlid: $this->id(),
            monsterName: $monster->name(),
            challengeRating: $monster->challengeRating()
        );
        $this->apply($event);
    }

    public function assignToParty(EncounterParty $party): void
    {
        $event = new PartyWasAssigned($this->id(), $party->partyUlid, $party->charactersLevel);
        $this->apply($event);
    }
}
