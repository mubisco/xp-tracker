<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Query;

use Doctrine\DBAL\Connection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class CharactersByPartyQueryHandler
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public function __invoke(CharactersByPartyQuery $query): array
    {
        $ulid = SharedUlid::fromString($query->partyUlid);
        $sql = "SELECT c.* 
            FROM `characters` c 
            LEFT JOIN party_character pc ON pc.character_id = c.character_id 
            WHERE pc.party_id = :partyUlid";
        $criteria = ['partyUlid' => $ulid->ulid()];
        $results = $this->connection->fetchAllAssociative($sql, $criteria);
        return $this->parseResults($results);
    }
    /**
     * @return array[]
     * @param array<int,mixed> $results
     */
    private function parseResults(array $results): array
    {
        $parsedResults = [];
        foreach ($results as $row) {
            $parsedResults[] = $this->parseRow($row['character_id'], $row['character_data']);
        }
        return $parsedResults;
    }
    /**
     * @return array<string,mixed>
     */
    private function parseRow(string $characterId, string $rawData): array
    {
        $parsedData = json_decode($rawData, true);
        return [
            'ulid' => $characterId,
            'name' => $parsedData['name'],
            'xp' => $parsedData['xp'],
            'next' => $parsedData['next'],
            'level' => $parsedData['level'],
        ];
    }
}
