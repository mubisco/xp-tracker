<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain;

use XpTracker\Encounter\Domain\Level\EncounterLevel;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Monster\MonsterWasRemoved;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\PartyAlreadyAssignedException;
use XpTracker\Encounter\Domain\Party\PartyNotAssignedToEncounterException;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;
use XpTracker\Encounter\Domain\Party\PartyWasUnassigned;
use XpTracker\Encounter\Domain\Party\PartyWasUpdated;
use XpTracker\Shared\Domain\AggregateRoot;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class BasicEncounter extends AggregateRoot implements Encounter
{
    private EncounterName $name;
    private ?EncounterParty $party = null;
    private EncounterLevel $level;
    /** @var array<int, EncounterMonster> */
    private array $monsters = [];
    private int $totalXpSolved = 0;

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
        $party = $this->party?->partyUlid ?? '';
        $level = $this->totalXpSolved > 0 ? 'RESOLVED' : $this->level->level()->value;
        return [
            'name' => $this->name->value(),
            'party' => $party,
            'level' => $level,
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
        $this->updateLevel();
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
        $this->updateLevel();
    }

    protected function applyPartyWasAssigned(PartyWasAssigned $event): void
    {
        if (null !== $this->party) {
            throw new PartyAlreadyAssignedException("This encounter has a party assigned!!!");
        }
        $this->party = new EncounterParty($event->partyUlid, $event->charactersLevel);
        $this->updateLevel();
    }

    private function updateLevel(): void
    {
        $monsterXps = $this->monsterXpValues();
        $characterLevels = null === $this->party ? [] : $this->party->charactersLevel;
        $this->level = EncounterLevel::fromValues($characterLevels, $monsterXps);
    }

    protected function applyPartyWasUnassigned(PartyWasUnassigned $event): void
    {
        if (null === $this->party) {
            throw new PartyNotAssignedToEncounterException("This encounter has no party assigned!!!");
        }
        if ($this->party->partyUlid !== $event->partyUlid) {
            throw new PartyNotAssignedToEncounterException("This encounter has a different party assigned!!!");
        }
        $this->party = null;
        $this->updateLevel();
    }

    protected function applyPartyWasUpdated(PartyWasUpdated $event): void
    {
        if (null === $this->party) {
            throw new PartyNotAssignedToEncounterException("This encounter has no assigned party to update!!!");
        }
        if ($this->party->partyUlid !== $event->partyUlid) {
            throw new PartyNotAssignedToEncounterException("This encounter has a different party assigned!!!");
        }
        $this->party = new EncounterParty($event->partyUlid, $event->charactersLevel);
        $this->updateLevel();
    }

    protected function applyEncounterWasSolved(EncounterWasSolved $event): void
    {
        $this->totalXpSolved = $event->totalXp;
    }

    private function monsterXpValues(): array
    {
        return array_map(function (EncounterMonster $monster) {
            return $monster->xp();
        }, $this->monsters);
    }

    public function addMonster(EncounterMonster $monster): void
    {
        $event = new MonsterWasAdded($this->id(), $monster->name(), $monster->challengeRating());
        $this->apply($event);
    }

    public function removeMonster(EncounterMonster $monster): void
    {
        $event = new MonsterWasRemoved($this->id(), $monster->name(), $monster->challengeRating());
        $this->apply($event);
    }

    public function assignToParty(EncounterParty $party): void
    {
        $event = new PartyWasAssigned($this->id(), $party->partyUlid, $party->charactersLevel);
        $this->apply($event);
    }

    public function unassign(SharedUlid $partyUlid): void
    {
        $event = new PartyWasUnassigned($this->id(), $partyUlid->ulid());
        $this->apply($event);
    }

    public function updateAssignedParty(EncounterParty $party): void
    {
        $event = new PartyWasUpdated($this->id(), $party->partyUlid, $party->charactersLevel);
        $this->apply($event);
        $encounterEvent = new EncounterWasUpdated($this->id());
        $this->apply($encounterEvent);
    }

    public function resolve(): void
    {
        if (null === $this->party) {
            throw new EncounterNotResolvableException("This encounter has no party assigned!!!");
        }
        $monsterXpValues = $this->monsterXpValues();
        $event = new EncounterWasSolved($this->id(), $this->party->partyUlid, array_sum($monsterXpValues));
        $this->apply($event);
    }
}
