<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class CharacterRepositoryStub implements CharacterRepository
{
    private readonly ?Character $character;

    public function __construct(?Character $character = null)
    {
        $this->character = $character;
    }

    public function byId(SharedUlid $ulid): Character
    {
        if (null !== $this->character) {
            return $this->character;
        }
        $builder = CharacterOM::aBuilder();
        return $builder->build();
    }

    public function ofPartyId(SharedUlid $ulid): array
    {
        $characters = [];
        for ($i = 0; $i < 3; $i++) {
            $builder = CharacterOM::aBuilder();
            $characters[] = $builder->build();
        }
        return $characters;
    }
}
