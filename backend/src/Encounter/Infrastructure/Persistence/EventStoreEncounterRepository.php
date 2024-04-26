<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Persistence;

use XpTracker\Encounter\Domain\AddEncounterWriteModel;
use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterAlreadyExistsException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Infrastructure\Persistence\EventStore;

final class EventStoreEncounterRepository implements AddEncounterWriteModel
{
    public function __construct(private readonly EventStore $eventStore)
    {
    }

    public function store(Encounter $encounter): void
    {
        $results = $this->eventStore->getEventsForUlid($encounter->id());
        if (!empty($results)) {
            throw new EncounterAlreadyExistsException(
                "There is an encounter with same id {$encounter->id()}"
            );
        }
        $collection = EventCollection::fromValues($encounter->id(), $encounter->pullEvents());
        $this->eventStore->appendEvents($collection);
    }
}
