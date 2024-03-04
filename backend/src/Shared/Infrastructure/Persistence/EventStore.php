<?php

declare(strict_types=1);

namespace XpTracker\Shared\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use JMS\Serializer\SerializerInterface;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EventStore
{
    public function __construct(
        private readonly Connection $connection,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function appendEvents(EventCollection $eventCollection): void
    {
        $aggregateId = $eventCollection->ulid();
        foreach ($eventCollection->events() as $event) {
            $serializedEvent = $this->serializer->serialize($event, 'json');
            $eventType = get_class($event);
            $data = [
                'aggregate_id' => $aggregateId,
                'created_at' => $event->occurredOn()->format('Y-m-d H:i:s'),
                'body' => $serializedEvent,
                'event_class' => $eventType,
            ];
            $this->connection->insert(data: $data, table: 'events');
        }
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public function getEventsForUlid(string $ulid): array
    {
        $sql = "SELECT * FROM events WHERE aggregate_id = :aggregateId ORDER BY created_at ASC";
        $params = ['aggregateId' => $ulid];
        return $this->connection->fetchAllAssociative($sql, $params);
    }

    public function eventCollection(SharedUlid $ulid): EventCollection
    {
        $results = $this->getEventsForUlid($ulid->ulid());
        $hydratedEvents = [];
        foreach ($results as $rawEvent) {
            $eventClass = $rawEvent['event_class'];
            $body = $rawEvent['body'];
            $event = $this->serializer->deserialize($body, $eventClass, 'json');
            $hydratedEvents[] = $event;
        }
        return EventCollection::fromValues($ulid->ulid(), $hydratedEvents);
    }
}
