<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Persistence;

use XpTracker\Encounter\Domain\AddEncounterWriteModel;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterAlreadyExistsException;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\UpdateEncounterWriteModel;
use XpTracker\Shared\Domain\Event\EmptyEventsForCollectionException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Infrastructure\Persistence\EventStore;

final class EventStoreEncounterRepository implements
    AddEncounterWriteModel,
    UpdateEncounterWriteModel,
    EncounterRepository
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

    public function update(Encounter $update): void
    {
        $results = $this->eventStore->getEventsForUlid($update->id());
        if (empty($results)) {
            throw new EncounterNotFoundException("No encounter found with {$update->id()} ulid for update");
        }
        $collection = EventCollection::fromValues($update->id(), $update->pullEvents());
        $this->eventStore->appendEvents($collection);
    }

    public function byEncounterId(SharedUlid $encounterId): Encounter
    {
        try {
            $eventCollection = $this->eventStore->eventCollection($encounterId);
            return BasicEncounter::fromEvents($eventCollection);
        } catch (EmptyEventsForCollectionException) {
            throw new EncounterNotFoundException("No encounter found with {$encounterId->ulid()} ulid");
        }
    }
}
