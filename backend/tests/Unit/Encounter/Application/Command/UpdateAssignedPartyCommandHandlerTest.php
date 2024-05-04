<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\UpdateAssignedPartyCommand;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasUpdated;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\Party\EncounterPartyNotFoundException;
use XpTracker\Encounter\Domain\Party\PartyAlreadyAssignedException;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Tests\Unit\Encounter\Domain\BasicEncounterOM;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\Party\EncounterPartyReadModelNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\Party\EncounterPartyReadModelStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;
use XpTracker\Encounter\Application\Command\UpdateAssignedPartyCommandHandler;

class UpdateAssignedPartyCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenWrongEncounterUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterPartyReadModelNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('encounterUlid', '01HWTX7R08E5TG1NATFWH43VDQ');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongPartyUlid(): void
    {
        $this->expectException(WrongEncounterPartyUlidException::class);
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterPartyReadModelNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', 'partyUlid');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterNotFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterPartyReadModelNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', '01HWTXCCE8NQMVYNMFKJ3WEBY5');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoPartyFound(): void
    {
        $this->expectException(EncounterPartyNotFoundException::class);
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryStub(),
            new EncounterPartyReadModelNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', '01HWTXCCE8NQMVYNMFKJ3WEBY5');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionIfPartyCannotBeAssigned(): void
    {
        $this->expectException(PartyAlreadyAssignedException::class);
        $ulid = '01HWTXCCE8NQMVYNMFKJ3WEBY5';
        $encounter = BasicEncounterOM::aBuilder()->withRandomParty()->build();
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryStub($encounter),
            new EncounterPartyReadModelStub(SharedUlid::fromString($ulid)),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', $ulid);
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterCannotBeUpdated(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $ulid = '01HWTXCCE8NQMVYNMFKJ3WEBY5';
        $encounter = BasicEncounterOM::aBuilder()->build();
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryStub($encounter),
            new EncounterPartyReadModelStub(SharedUlid::fromString($ulid)),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', $ulid);
        ($sut)($command);
    }

    /** @test */
    public function itShouldAssignPartyProperly(): void
    {
        $ulid = '01HWTXCCE8NQMVYNMFKJ3WEBY5';
        $encounter = BasicEncounterOM::aBuilder()->build();
        $spy = new UpdateEncounterWriteModelSpy();
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryStub($encounter),
            new EncounterPartyReadModelStub(SharedUlid::fromString($ulid)),
            $spy,
            new EventBusSpy()
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', $ulid);
        ($sut)($command);
        $this->assertNotNull($spy->updatedEncounter);
    }

    /** @test */
    public function itShouldSendEvents(): void
    {
        $ulid = '01HWTXCCE8NQMVYNMFKJ3WEBY5';
        $encounter = BasicEncounterOM::aBuilder()->build();
        $spy = new EventBusSpy();
        $sut = new UpdateAssignedPartyCommandHandler(
            new EncounterRepositoryStub($encounter),
            new EncounterPartyReadModelStub(SharedUlid::fromString($ulid)),
            new UpdateEncounterWriteModelSpy(),
            $spy
        );
        $command = new UpdateAssignedPartyCommand('01HWTX9KJGDASR5P8T7R7BCJ8E', $ulid);
        ($sut)($command);
        $this->assertCount(2, $spy->publishedEvents);
        $this->assertInstanceOf(PartyWasAssigned::class, $spy->publishedEvents[0]);
        $this->assertInstanceOf(EncounterWasUpdated::class, $spy->publishedEvents[1]);
    }
}
