<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyProjectionException;
use XpTracker\Character\Domain\Party\PartyProjection;

final class DbalUpdateWhenCharacterAddedPartyProjection implements PartyProjection
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(Party $party): void
    {
        $this->checkPartyExists($party->id());
        $this->updatePartyCharacters($party);
    }

    private function checkPartyExists(string $partyId): void
    {
        $sql = "SELECT party_id FROM party WHERE party_id = :partyId";
        $params = ['partyId' => $partyId];
        $result = $this->connection->fetchOne($sql, $params);
        if (false === $result) {
            throw new PartyProjectionException("Party {$partyId} should exist!");
        }
    }

    private function updatePartyCharacters(Party $party): void
    {
        $partyData = json_decode($party->toJson(), true);
        $characters = isset($partyData['characters']) ? $partyData['characters'] : [];
        foreach ($characters as $characterId) {
            $this->insertRelation($party->id(), $characterId);
        }
    }

    private function insertRelation(string $partyId, string $characterId): void
    {
        $characterIdAlreadyExists = $this->checkCharacterRelationAlreadyExists($characterId);
        if ($characterIdAlreadyExists) {
            return;
        }
        $this->insertCharacterId($partyId, $characterId);
    }

    private function checkCharacterRelationAlreadyExists(string $characterId): bool
    {
        $sql = "SELECT party_id FROM party_character WHERE character_id = :characterId";
        $params = ['characterId' => $characterId];
        $result = $this->connection->fetchOne($sql, $params);
        return false !== $result;
    }

    private function insertCharacterId(string $partyId, string $characterId): void
    {
        try {
            $data = [
                'party_id' => $partyId,
                'character_id' => $characterId
            ];
            $this->connection->insert('party_character', $data);
        } catch (\Exception $e) {
            throw new PartyProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
