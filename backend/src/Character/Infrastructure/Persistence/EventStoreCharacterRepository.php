<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use XpTracker\Character\Domain\AddCharacterWriteModel;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterAlreadyExistsException;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\UpdateCharacterPartyWriteModel;
use XpTracker\Shared\Domain\Event\EmptyEventsForCollectionException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Infrastructure\Persistence\EventStore;

final class EventStoreCharacterRepository implements
    AddCharacterWriteModel,
    CharacterRepository,
    UpdateCharacterPartyWriteModel
{
    public function __construct(
        private readonly EventStore $eventStore,
        private readonly Connection $connection
    ) {
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

    public function updateCharacterParty(Character $character): void
    {
        $results = $this->eventStore->getEventsForUlid($character->id());
        if (empty($results)) {
            throw new CharacterNotFoundException("Character with {$character->id()} does not exists");
        }
        $collection = EventCollection::fromValues($character->id(), $character->pullEvents());
        $this->eventStore->appendEvents($collection);
    }

    public function ofPartyId(SharedUlid $ulid): array
    {
        $characterIds = $this->partyCharacterIds($ulid);
        $characters = [];
        foreach ($characterIds as $characterId) {
            $characterUlid = SharedUlid::fromString($characterId);
            $characters[] = $this->byId($characterUlid);
        }
        return $characters;
    }
    /**
     * @return array<int,string>
     */
    private function partyCharacterIds(SharedUlid $ulid): array
    {
        $sql = "SELECT * FROM party_character WHERE party_id = :partyUlid";
        $criteria = ['partyUlid' => $ulid->ulid()];
        $results = $this->connection->fetchAllAssociative($sql, $criteria);
        $ids = [];
        if (empty($results)) {
            throw new PartyNotFoundException("No characters found for party {$ulid->ulid()}");
        }
        foreach ($results as $result) {
            $ids[] = $result['character_id'];
        }
        return $ids;
    }
}
