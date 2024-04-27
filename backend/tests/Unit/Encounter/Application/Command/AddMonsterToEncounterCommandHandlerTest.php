<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\AddMonsterToEncounterCommand;
use XpTracker\Encounter\Application\Command\AddMonsterToEncounterCommandHandler;
use XpTracker\Encounter\Domain\Monster\WrongMonsterValueException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;

class AddMonsterToEncounterCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenInvalidValues(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = new AddMonsterToEncounterCommandHandler();
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: 'asd',
            monsterName: 'pipas',
            experiencePoints: 150,
            challengeRating: '1/2'
        );
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoMonsterCouldBeCreated(): void
    {
        $this->expectException(WrongMonsterValueException::class);
        $sut = new AddMonsterToEncounterCommandHandler();
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            experiencePoints: 150,
            challengeRating: '1/2'
        );
        ($sut)($command);
    }
}
