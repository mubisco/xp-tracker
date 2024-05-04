<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Event;

use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\MessageBusInterface;
use XpTracker\Encounter\Application\Command\UpdateAssignedPartyCommand;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class UpdateEncountersWhenPartyLevelWasUpdatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly Connection $connection
    ) {
    }

    public function __invoke(PartyLevelWasUpdated $event): void
    {
        $partyUlid = SharedUlid::fromString($event->partyUlid);
        $encountersToUpdate = $this->getActiveEncountersByPartyId($partyUlid);
        foreach ($encountersToUpdate as $encounterId) {
            $command = new UpdateAssignedPartyCommand($encounterId, $event->partyUlid);
            $this->commandBus->dispatch($command);
        }
    }

    private function getActiveEncountersByPartyId(SharedUlid $partyId): array
    {
        $sql = "SELECT encounter_id FROM encounter WHERE party_id = :partyId AND status != :status";
        $criteria = [
            'partyId' => $partyId->ulid(),
            'status' => 'RESOLVED'
        ];
        $results = $this->connection->fetchAllAssociative($sql, $criteria);
        if (false === $results) {
            return [];
        }
        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result['encounter_id'];
        }
        return $ids;
    }
}
