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
        try {
            $data = [
                'character_id' => $character->id(),
                'character_data' => $character->toJson()
            ];
            // TODO: comprobación previo si existe o tal vez tener 2 projections, la de create y la de update
            $this->connection->insert('characters', $data);
        } catch (Exception $e) {
            throw new CharacterProjectionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
