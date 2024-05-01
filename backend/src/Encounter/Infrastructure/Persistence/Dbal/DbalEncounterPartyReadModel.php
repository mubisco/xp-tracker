<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Persistence\Dbal;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\EncounterPartyNotFoundException;
use XpTracker\Encounter\Domain\Party\EncounterPartyReadModel;
use XpTracker\Encounter\Domain\Party\EncounterPartyReadModelException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class DbalEncounterPartyReadModel implements EncounterPartyReadModel
{
    public function __construct(private readonly Connection $connection)
    {
    }
    public function byPartyId(SharedUlid $partyUlid): EncounterParty
    {
        $sql = "SELECT c.* FROM characters c
            INNER JOIN party_character pc ON pc.character_id = c.character_id
            WHERE pc.party_id = :partyId";
        $criteria = ['partyId' => $partyUlid->ulid()];
        $results = $this->connection->fetchAllAssociative($sql, $criteria);
        if (empty($results)) {
            throw new EncounterPartyNotFoundException(
                'No party found with ulid ' . $partyUlid->ulid()
            );
        }
        try {
                $characterLevels = $this->parseResultsIntoCharacterLevels($results);
                return new EncounterParty($partyUlid->ulid(), $characterLevels);
        } catch (InvalidArgumentException) {
            throw new EncounterPartyReadModelException(
                "Error parsing data of party {$partyUlid->ulid()}"
            );
        }
    }
    /**
     * @return int[]
     * @param array<int,mixed> $results
     */
    private function parseResultsIntoCharacterLevels(array $results): array
    {
        $levels = [];
        foreach ($results as $singleRow) {
            $levels[] = $this->extractCharacterLevel($singleRow);
        }
        return $levels;
    }
    /**
     * @param array<string,mixed> $singleRow
     */
    private function extractCharacterLevel(array $singleRow): int
    {
        $data = json_decode($singleRow['character_data'], true);
        if (!$data || !isset($data['level'])) {
            throw new InvalidArgumentException();
        }
        return (int) $data['level'];
    }
}
