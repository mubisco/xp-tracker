<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Query;

use Doctrine\DBAL\Connection;

final class FindAllPartiesQueryHandler
{
    public function __construct(private readonly Connection $connection)
    {
    }
    /**
     * @return array<int,array<string,mixed>>
     */
    public function __invoke(FindAllPartiesQuery $query): array
    {
        $sql = "SELECT p.party_id AS partyUlid, p.name AS partyName, COUNT(pc.character_id) AS partyCharacters
            FROM party p LEFT JOIN party_character pc ON pc.party_id = p.party_id
            GROUP BY pc.party_id;";
        return $this->connection->fetchAllAssociative($sql);
    }
}
