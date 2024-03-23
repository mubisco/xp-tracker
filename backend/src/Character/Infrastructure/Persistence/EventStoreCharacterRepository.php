<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use XpTracker\Character\Domain\AddCharacterWriteModel;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterAlreadyExistsException;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Shared\Domain\Event\EmptyEventsForCollectionException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Infrastructure\Persistence\EventStore;

final class EventStoreCharacterRepository implements AddCharacterWriteModel, CharacterRepository
{
    public function __construct(private readonly EventStore $eventStore)
    {
    }

    public function add(Character $character): void
    {
        $results = $this->eventStore->getEventsForUlid($character->id());
        if (!empty($results)) {
            throw new CharacterAlreadyExistsException("A character with {$character->id()} already exists");
        }
        $collection = EventCollection::fromValues($character->id(), $character->pullEvents());
        $this->eventStore->appendEvents($collection);
    }

    public function byId(SharedUlid $ulid): Character
    {
        try {
            $eventCollection = $this->eventStore->eventCollection($ulid);
            return BasicCharacter::fromEvents($eventCollection);
        } catch (EmptyEventsForCollectionException) {
            throw new CharacterNotFoundException("No character found with ulid {$ulid->ulid()}");
        }
    }
}
