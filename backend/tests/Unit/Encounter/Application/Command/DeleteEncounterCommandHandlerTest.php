<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\DeleteEncounterCommand;
use XpTracker\Encounter\Application\Command\DeleteEncounterCommandHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterNotResolvableException;
use XpTracker\Encounter\Domain\EncounterWasDeleted;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\BasicEncounterOM;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class DeleteEncounterCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenWrongValues(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = new DeleteEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new DeleteEncounterCommand('asd'));
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterNotFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $sut = new DeleteEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new DeleteEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterCannotBeUpdated(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $encounter = BasicEncounterOM::aBuilder()->withRandomMonster()->withRandomParty()->build();
        $sut = new DeleteEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new DeleteEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
    }

    /** @test */
    public function itShouldUpdateEncounterSuccessfully(): void
    {
        $spy = new UpdateEncounterWriteModelSpy();
        $encounter = BasicEncounterOM::aBuilder()->withRandomMonster()->withRandomParty()->build();
        $sut = new DeleteEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            $spy,
            new EventBusSpy()
        );
        ($sut)(new DeleteEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
        $this->assertEquals('DELETED', $spy->updatedEncounter->status());
    }

    /** @test */
    public function itShouldSendProperEvents(): void
    {
        $spy = new EventBusSpy();
        $encounter = BasicEncounterOM::aBuilder()->withRandomMonster()->withRandomParty()->build();
        $sut = new DeleteEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelSpy(),
            $spy
        );
        ($sut)(new DeleteEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
        $this->assertCount(1, $spy->publishedEvents);
        $this->assertInstanceOf(EncounterWasDeleted::class, $spy->publishedEvents[0]);
    }
}
