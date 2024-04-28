<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\AddMonsterToEncounterCommand;
use XpTracker\Encounter\Application\Command\AddMonsterToEncounterCommandHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Monster\WrongMonsterValueException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class AddMonsterToEncounterCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenInvalidValues(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = new AddMonsterToEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: 'asd',
            monsterName: 'pipas',
            challengeRating: '1/2'
        );
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoMonsterCouldBeCreated(): void
    {
        $this->expectException(WrongMonsterValueException::class);
        $sut = new AddMonsterToEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '-1'
        );
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterNotFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $sut = new AddMonsterToEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '1'
        );
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterCannotBeUpdated(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $sut = new AddMonsterToEncounterCommandHandler(
            new EncounterRepositoryStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '1'
        );
        ($sut)($command);
    }

    /** @test */
    public function itShouldUpdateEncounterProperly(): void
    {
        $spy = new UpdateEncounterWriteModelSpy();
        $sut = new AddMonsterToEncounterCommandHandler(
            new EncounterRepositoryStub(),
            $spy,
            new EventBusSpy()
        );
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '1'
        );
        ($sut)($command);
        $this->assertNotNull($spy->updatedEncounter);
        $events = $spy->updatedEncounter->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MonsterWasAdded::class, $events[0]);
    }

    /** @test */
    public function itShouldPublishEvents(): void
    {
        $spy = new EventBusSpy();
        $sut = new AddMonsterToEncounterCommandHandler(
            new EncounterRepositoryStub(),
            new UpdateEncounterWriteModelSpy(),
            $spy
        );
        $command = new AddMonsterToEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '1'
        );
        ($sut)($command);
        $events = $spy->publishedEvents;
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MonsterWasAdded::class, $events[0]);
    }
}
