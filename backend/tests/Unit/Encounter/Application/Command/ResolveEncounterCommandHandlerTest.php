<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\ResolveEncounterCommand;
use XpTracker\Encounter\Application\Command\ResolveEncounterCommandHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterNotResolvableException;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\BasicEncounterOM;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class ResolveEncounterCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenWrongValues(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = new ResolveEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new ResolveEncounterCommand('asd'));
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterNotFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $sut = new ResolveEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new ResolveEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterNotResolvable(): void
    {
        $this->expectException(EncounterNotResolvableException::class);
        $encounter = BasicEncounterOM::aBuilder()->build();
        $sut = new ResolveEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new ResolveEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterCannotBeUpdated(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $encounter = BasicEncounterOM::aBuilder()->withRandomMonster()->withRandomParty()->build();
        $sut = new ResolveEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        ($sut)(new ResolveEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
    }

    /** @test */
    public function itShouldUpdateEncounterSuccessfully(): void
    {
        $spy = new UpdateEncounterWriteModelSpy();
        $encounter = BasicEncounterOM::aBuilder()->withRandomMonster()->withRandomParty()->build();
        $sut = new ResolveEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            $spy,
            new EventBusSpy()
        );
        ($sut)(new ResolveEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
        $this->assertEquals('RESOLVED', $spy->updatedEncounter->status());
    }

    /** @test */
    public function itShouldSendProperEvents(): void
    {
        $spy = new EventBusSpy();
        $encounter = BasicEncounterOM::aBuilder()->withRandomMonster()->withRandomParty()->build();
        $sut = new ResolveEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelSpy(),
            $spy
        );
        ($sut)(new ResolveEncounterCommand('01HWTTCB6GB9MHMBA4MN6QRBJT'));
        $this->assertCount(1, $spy->publishedEvents);
        $this->assertInstanceOf(EncounterWasSolved::class, $spy->publishedEvents[0]);
    }
}
