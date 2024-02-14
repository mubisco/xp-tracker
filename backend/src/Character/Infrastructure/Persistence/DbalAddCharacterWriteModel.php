<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use JMS\Serializer\SerializerInterface;
use XpTracker\Character\Domain\AddCharacterWriteModel;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterAlreadyExistsException;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class DbalAddCharacterWriteModel implements AddCharacterWriteModel, CharacterRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function add(Character $character): void
    {
        $results = $this->getEventsForUlid($character->id());
        if (!empty($results)) {
            throw new CharacterAlreadyExistsException("A character with {$character->id()} already exists");
        }
        $events = $character->pullEvents();
        foreach ($events as $event) {
            $serializedEvent = $this->serializer->serialize($event, 'json');
            $id = $character->id();
            $eventType = get_class($event);
            $data = [
                'aggregate_id' => $id,
                'created_at' => $event->occurredOn()->format('Y-m-d H:i:s'),
                'body' => $serializedEvent,
                'event_class' => $eventType,
            ];
            $this->connection->insert(data: $data, table: 'events');
        }
    }

    public function byId(SharedUlid $ulid): Character
    {
        $results = $this->getEventsForUlid($ulid->ulid());
        if (empty($results)) {
            throw new CharacterNotFoundException("No character found with ulid {$ulid->ulid()}");
        }
        $hydratedEvents = [];
        foreach ($results as $rawEvent) {
            $eventClass = $rawEvent['event_class'];
            $body = $rawEvent['body'];
            $event = $this->serializer->deserialize($body, $eventClass, 'json');
            $hydratedEvents[] = $event;
        }
        return BasicCharacter::fromEvents($ulid, $hydratedEvents);
    }
    /**
     * @return array<int,array<string,mixed>>
     */
    private function getEventsForUlid(string $ulid): array
    {
        $sql = "SELECT * FROM events WHERE aggregate_id = :aggregateId ORDER BY created_at ASC";
        $params = ['aggregateId' => $ulid];
        return $this->connection->fetchAllAssociative($sql, $params);
    }
}
