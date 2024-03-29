<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Exception;
use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterProjection;
use XpTracker\Character\Domain\CharacterProjectionException;

final class DbalCharacterProjection implements CharacterProjection
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(Character $character): void
    {
        $sql = "SELECT character_id FROM characters WHERE character_id = :characterId";
        $params = ['characterId' => $character->id()];
        $result = $this->connection->fetchOne($sql, $params);
        if (false === $result) {
            $this->insertCharacter($character);
            return;
        }
        $this->updateCharacter($character);
    }

    private function insertCharacter(Character $character): void
    {
        try {
            $data = [
                'character_id' => $character->id(),
                'character_data' => $character->toJson()
            ];
            $this->connection->insert('characters', $data);
        } catch (Exception $e) {
            throw new CharacterProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function updateCharacter(Character $character): void
    {
        try {
            $data = ['character_data' => $character->toJson()];
            $criteria = ['character_id' => $character->id()];
            $this->connection->update(table: 'characters', data: $data, criteria: $criteria);
        } catch (Exception $e) {
            throw new CharacterProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
