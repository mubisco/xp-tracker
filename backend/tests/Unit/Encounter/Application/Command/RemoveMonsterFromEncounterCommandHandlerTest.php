<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\RemoveMonsterFromEncounterCommand;
use XpTracker\Encounter\Application\Command\RemoveMonsterFromEncounterCommandHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\EncounterWasUpdated;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasRemoved;
use XpTracker\Encounter\Domain\Monster\WrongMonsterValueException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterOM;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class RemoveMonsterFromEncounterCommandHandlerTest extends TestCase
{
    private RemoveMonsterFromEncounterCommand $command;

    protected function setUp(): void
    {
        $this->command = new RemoveMonsterFromEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '1'
        );
    }
    /**
     * @test
     */
    public function itShouldThrowExceptionWhenInvalidValues(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = $this->buildFailingSut();
        $command = new RemoveMonsterFromEncounterCommand(
            encounterUlid: 'asd',
            monsterName: 'pipas',
            challengeRating: '1/2'
        );
        ($sut)($command);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionWhenNoMonsterCouldBeCreated(): void
    {
        $this->expectException(WrongMonsterValueException::class);
        $sut = $this->buildFailingSut();
        $command = new RemoveMonsterFromEncounterCommand(
            encounterUlid: '01HWFXX0DRRSY20MA6DQHBGAHW',
            monsterName: 'pipas',
            challengeRating: '-1'
        );
        ($sut)($command);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionWhenEncounterNotFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $sut = $this->buildFailingSut();
        ($sut)($this->command);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionWhenEncounterCannotBeUpdated(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $sut = new RemoveMonsterFromEncounterCommandHandler(
            new EncounterRepositoryStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)($this->command);
    }

    /**
     * @test
     */
    public function itShouldUpdateEncounterProperly(): void
    {
        $spy = new UpdateEncounterWriteModelSpy();
        $repository = $this->buildRepositoryWithOneEncounterMonster();
        $sut = new RemoveMonsterFromEncounterCommandHandler(
            $repository,
            $spy,
            new EventBusSpy()
        );
        ($sut)($this->command);
        $this->assertNotNull($spy->updatedEncounter);
    }

    /**
     * @test
     */
    public function itShouldPublishEvents(): void
    {
        $spy = new EventBusSpy();
        $repository = $this->buildRepositoryWithOneEncounterMonster();
        $sut = new RemoveMonsterFromEncounterCommandHandler(
            $repository,
            new UpdateEncounterWriteModelSpy(),
            $spy
        );
        ($sut)($this->command);
        $events = $spy->publishedEvents;
        $this->assertCount(2, $events);
        $this->assertInstanceOf(MonsterWasRemoved::class, $events[0]);
        $this->assertInstanceOf(EncounterWasUpdated::class, $spy->publishedEvents[1]);
    }

    private function buildFailingSut(): RemoveMonsterFromEncounterCommandHandler
    {
        $sut = new RemoveMonsterFromEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        return $sut;
    }

    private function buildRepositoryWithOneEncounterMonster(): EncounterRepository
    {
        $monster = EncounterMonster::fromStringValues(
            name: $this->command->monsterName,
            challengeRating: $this->command->challengeRating
        );
        $encounter = EncounterOM::withMonster($monster);
        return new EncounterRepositoryStub($encounter);
    }
}
