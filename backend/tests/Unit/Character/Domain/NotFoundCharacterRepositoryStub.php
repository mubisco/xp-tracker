<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class NotFoundCharacterRepositoryStub implements CharacterRepository
{
    public function byId(SharedUlid $ulid): Character
    {
        throw new CharacterNotFoundException('');
    }

    public function ofPartyId(SharedUlid $ulid): array
    {
        throw new PartyNotFoundException('');
    }
}
