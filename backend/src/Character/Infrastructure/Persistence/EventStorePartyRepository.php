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
        $results = $this->eventStore->getEventsForUlid($party->id());
        if (!empty($results)) {
            throw new PartyAlreadyExistsException("A party with {$party->id()} already exists");
        }
        $collection = new EventCollection($party->id(), $party->pullEvents());
        $this->eventStore->appendEvents($collection);
    }
}
