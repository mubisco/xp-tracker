<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\FullPartyReadModel;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class FullPartyReadModelStub implements FullPartyReadModel
{
    public function byUlid(SharedUlid $ulid): array
    {
        return [
            'partyId' => $ulid->ulid(),
            'name' => 'Comando G',
            'characters' => [
                '01HTA1M73R0EE324GSETEBW49D' => [
                    'characterId' => '01HTA1M73R0EE324GSETEBW49D',
                    'name' => 'Darling',
                    'experiencePoints' => 345,
                    'level' => 2
                ],
                '01HTA1MA1G1GF0WY9B6P75XG3P' => [
                    'characterId' => '01HTA1MA1G1GF0WY9B6P75XG3P',
                    'name' => 'Gatito',
                    'experiencePoints' => 355,
                    'level' => 2
                ]
            ]
        ];
    }
}
