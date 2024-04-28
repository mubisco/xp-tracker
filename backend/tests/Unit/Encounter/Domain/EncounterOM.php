<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EncounterOM
{
    public static function random(): Encounter
    {
        $faker = \Faker\Factory::create();
        $id = SharedUlid::fromEmpty();
        $name = $faker->word();
        $event = new EncounterWasCreated(id: $id->ulid(), name: $name);
        $collection = EventCollection::fromValues($id->ulid(), [$event]);
        return BasicEncounter::fromEvents($collection);
    }
}
