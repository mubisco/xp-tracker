<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use Faker\Factory;
use Faker\Generator;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class BasicEncounterOM
{
    private Generator $faker;
    private SharedUlid $encounterUlid;
    private string $name;
    private array $monsters;
    private ?EncounterParty $party;

    public static function aBuilder(): static
    {
        return new static();
    }

    private function __construct()
    {
        $this->faker = Factory::create();
        $this->encounterUlid = SharedUlid::fromEmpty();
        $this->name = $this->faker->word();
        $this->monsters = [];
        $this->party = null;
    }

    public function withMonster(EncounterMonster $monster): BasicEncounterOM
    {
        $this->monsters[] = $monster;
        return $this;
    }

    public function withParty(EncounterParty $encounterParty): BasicEncounterOM
    {
        $this->party = $encounterParty;
        return $this;
    }

    public function build(): BasicEncounter
    {
        $collection = $this->buildEventCollection();
        return BasicEncounter::fromEvents($collection);
    }

    private function buildEventCollection(): EventCollection
    {
        $event = new EncounterWasCreated(id: $this->encounterUlid->ulid(), name: $this->name);
        $monsterEvents = $this->parseMonsters();
        $events = array_merge([$event], $monsterEvents);
        if (null !== $this->party) {
            $events[] = new PartyWasAssigned(
                $this->encounterUlid->ulid(),
                $this->party->partyUlid,
                $this->party->charactersLevel
            );
        }
        return EventCollection::fromValues($this->encounterUlid->ulid(), $events);
    }

    /**
     * @return MonsterWasAdded[]
     */
    private function parseMonsters(): array
    {
        $events = [];
        foreach ($this->monsters as $monster) {
            $events[] = new MonsterWasAdded(
                encounterUlid: $this->encounterUlid->ulid(),
                monsterName: $monster->name(),
                challengeRating: $monster->challengeRating()
            );
        }
        return $events;
    }
}
