<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyProjection;
use XpTracker\Character\Domain\Party\PartyProjectionException;

final class DbalCreatePartyProjection implements PartyProjection
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(Party $party): void
    {
        $this->checkPartyDoesNotExists($party->id());
        $this->insertParty($party);
    }

    private function checkPartyDoesNotExists(string $partyId): void
    {
        $sql = "SELECT party_id FROM party WHERE party_id = :partyId";
        $params = ['partyId' => $partyId];
        $result = $this->connection->fetchOne($sql, $params);
        if (false !== $result) {
            throw new PartyProjectionException("Party {$partyId} should not exist!");
        }
    }

    private function insertParty(Party $party): void
    {
        try {
            $partyData = $party->toJson();
            $data = [
                'party_id' => $party->id(),
                'name' => isset($partyData['name']) ? $partyData['name'] : ''
            ];
            $this->connection->insert('party', $data);
        } catch (\Exception $e) {
            throw new PartyProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
