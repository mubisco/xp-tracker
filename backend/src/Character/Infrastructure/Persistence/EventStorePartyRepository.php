<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use XpTracker\Character\Domain\Party\AddCharacterToPartyWriteModel;
use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\CreatePartyWriteModel;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyAlreadyExistsException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Shared\Domain\Event\EmptyEventsForCollectionException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Infrastructure\Persistence\EventStore;

final class EventStorePartyRepository implements CreatePartyWriteModel, PartyRepository, AddCharacterToPartyWriteModel
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
        $collection = EventCollection::fromValues($party->id(), $party->pullEvents());
        $this->eventStore->appendEvents($collection);
    }

    public function byUlid(SharedUlid $ulid): Party
    {
        try {
            $eventCollection = $this->eventStore->eventCollection($ulid);
            return BasicParty::fromEvents($eventCollection);
        } catch (EmptyEventsForCollectionException) {
            throw new PartyNotFoundException("No party found with id {$ulid->ulid()}");
        }
    }

    public function updateCharacters(Party $party): void
    {
        $collection = EventCollection::fromValues($party->id(), $party->pullEvents());
        $this->eventStore->appendEvents($collection);
    }
}
