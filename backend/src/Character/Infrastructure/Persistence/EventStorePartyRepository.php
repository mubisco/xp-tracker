<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use XpTracker\Character\Domain\Party\CreatePartyWriteModel;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyAlreadyExistsException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Infrastructure\Persistence\EventStore;

final class EventStorePartyRepository implements CreatePartyWriteModel
{
    public function __construct(private readonly EventStore $eventStore)
    {
    }

    public function createParty(Party $party): void
    {
        $collection = EventCollection::fromValues($party->id(), $party->pullEvents());
        if (!empty($collection->events())) {
            throw new PartyAlreadyExistsException("A party with {$party->id()} already exists");
        }
        $this->eventStore->appendEvents($collection);
    }
}
