<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Persistence\Dbal;

use Doctrine\DBAL\Connection;
use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\Projection\CreatedEncounterProjection;
use XpTracker\Encounter\Domain\Projection\EncounterProjectionException;
use XpTracker\Encounter\Domain\Projection\ResolvedEncounterProjection;
use XpTracker\Encounter\Domain\Projection\UpdatedEncounterProjection;

final class DbalEncounterProjection implements
    CreatedEncounterProjection,
    UpdatedEncounterProjection,
    ResolvedEncounterProjection
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function projectCreated(Encounter $encounter): void
    {
        if ($this->checkEncounterExists($encounter->id())) {
            throw new EncounterProjectionException(
                "Encounter {$encounter->id()} already exists"
            );
        }
        $this->insertNewEncounter($encounter->id(), $encounter->partyId(), $encounter->status(), $encounter->toJson());
    }

    public function projectUpdate(Encounter $encounter): void
    {
        if (!$this->checkEncounterExists($encounter->id())) {
            throw new EncounterProjectionException(
                "Encounter {$encounter->id()} does not exists"
            );
        }
        $this->updateEncounter($encounter->id(), $encounter->partyId(), $encounter->status(), $encounter->toJson());
    }

    public function projectResolved(Encounter $encounter): void
    {
        if (!$this->checkEncounterExists($encounter->id())) {
            throw new EncounterProjectionException(
                "Encounter {$encounter->id()} does not exists"
            );
        }
        $this->updateEncounter($encounter->id(), $encounter->partyId(), $encounter->status(), $encounter->toJson());
    }

    private function checkEncounterExists(string $encounterId): bool
    {
        $sql = "SELECT encounter_id FROM encounter WHERE encounter_id = :encounterId";
        $criteria = ['encounterId' => $encounterId];
        $result = $this->connection->fetchOne($sql, $criteria);
        return $result !== false;
    }

    private function insertNewEncounter(string $encounterId, string $partyId, string $status, string $data): void
    {
        try {
            $data = [
                'encounter_id' => $encounterId,
                'party_id' => $partyId,
                'status' => $status,
                'data' => $data
            ];
            $this->connection->insert('encounter', $data);
        } catch (\Exception $e) {
            throw new EncounterProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function updateEncounter(string $encounterId, string $partyId, string $status, string $data): void
    {
        try {
            $data = ['party_id' => $partyId, 'status' => $status, 'data' => $data];
            $criteria = ['encounter_id' => $encounterId];
            $this->connection->update(table: 'encounter', data: $data, criteria: $criteria);
        } catch (\Exception $e) {
            throw new EncounterProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
