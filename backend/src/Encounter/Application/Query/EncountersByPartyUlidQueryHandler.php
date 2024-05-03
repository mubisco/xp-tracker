<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Query;

use Doctrine\DBAL\Connection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

class EncountersByPartyUlidQueryHandler
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @return array<int,mixed>
     */
    public function __invoke(EncountersByPartyUlidQuery $query): array
    {
        $ulid = SharedUlid::fromString($query->partyUlid);
        return $this->fetchEncounters($ulid);
    }

    /**
     * @return array<int,mixed>
     */
    private function fetchEncounters(SharedUlid $ulid): array
    {
        $sql = "SELECT * FROM encounter WHERE party_id = :partyUlid";
        $criteria = ['partyUlid' => $ulid->ulid()];
        $results = $this->connection->fetchAllAssociative($sql, $criteria);
        return $this->parseResults($results);
    }
    /**
     * @param array<int,mixed> $results
     * @return array<int,mixed>
     */
    private function parseResults(array $results): array
    {
        $parsedResults = [];
        foreach ($results as $result) {
            $encounterData = $this->deserializeData($result['data'] ?? []);
            $encounterData['ulid'] = $result['encounter_id'];
            $parsedResults[] = $encounterData;
        }
        return $parsedResults;
    }
    /**
     * @return array<string,mixed>
     */
    private function deserializeData(string $serializedData): array
    {
        $deserializedData = json_decode($serializedData, true);
        if (!$deserializedData) {
            return [];
        }
        return $deserializedData;
    }
}
