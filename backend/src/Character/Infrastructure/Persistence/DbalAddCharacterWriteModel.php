<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use JMS\Serializer\SerializerInterface;
use XpTracker\Character\Domain\AddCharacterWriteModel;
use XpTracker\Character\Domain\Character;

final class DbalAddCharacterWriteModel implements AddCharacterWriteModel
{
    public function __construct(
        private readonly Connection $connection,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function add(Character $character): void
    {
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
}
