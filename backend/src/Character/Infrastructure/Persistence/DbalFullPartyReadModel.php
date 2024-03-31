<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use XpTracker\Character\Domain\Party\FullPartyReadModel;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyReadModelException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class DbalFullPartyReadModel implements FullPartyReadModel
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function byUlid(SharedUlid $ulid): array
    {
        $partyData = $this->partyData($ulid);
        $characters = $this->charactersFromParty($ulid);
        $partyData['characters'] = $characters;
        return $partyData;
    }
    /**
     * @return array<string,mixed>
     */
    private function partyData(SharedUlid $ulid): array
    {
        $sql = "SELECT party_id AS partyId, name AS name FROM party WHERE party_id = :partyId";
        $params = ['partyId' => $ulid->ulid()];
        $results = $this->connection->fetchAllAssociative($sql, $params);
        if (empty($results)) {
            throw new PartyNotFoundException("No party found with id {$ulid->ulid()}");
        }
        return $results[0];
    }
    /**
     * @return array<string,array<string,mixed>
     */
    private function charactersFromParty(SharedUlid $ulid): array
    {
        $sql = "SELECT c.* FROM characters c
            INNER JOIN party_character pc ON pc.character_id = c.character_id
            WHERE pc.party_id = :partyId";
        $params = ['partyId' => $ulid->ulid()];
        $results = $this->connection->fetchAllAssociative($sql, $params);
        $parsedResults = [];
        foreach ($results as $singleRow) {
            $characterId = $this->parseCharacterId($singleRow);
            $parsedResults[$characterId] = $this->parseSingleRow($characterId, $singleRow);
        }
        return $parsedResults;
    }

    /**
     * @param array<int,mixed> $singleRow
     */
    private function parseCharacterId(array $singleRow): string
    {
        if (!isset($singleRow['character_id'])) {
            throw new PartyReadModelException('');
        }
        return (string) $singleRow['character_id'];
    }

    /**
     * @return array<string,mixed>
     * @param array<int,mixed> $singleRow
     */
    private function parseSingleRow(string $characterId, array $singleRow): array
    {
        $data = json_decode($singleRow['character_data'], true);
        if (!$data) {
            throw new PartyReadModelException('');
        }
        $data['characterId'] = $characterId;
        return $data;
    }
}
