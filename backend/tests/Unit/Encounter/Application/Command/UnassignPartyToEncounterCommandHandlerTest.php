<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\UnassignPartyToEncounterCommand;
use XpTracker\Encounter\Application\Command\UnassignPartyToEncounterCommandHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasUpdated;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\PartyNotAssignedToEncounterException;
use XpTracker\Encounter\Domain\Party\PartyWasUnassigned;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\BasicEncounterOM;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\UpdateEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class UnassignPartyToEncounterCommandHandlerTest extends TestCase
{
    private const PARTY_ULID = '01HWTXCCE8NQMVYNMFKJ3WEBY5';
    private const ENCOUNTER_ULID = '01HWWCDBM0CCFA7NZYNPAPSXPR';
    /** @test */
    public function itShouldThrowExceptionWhenWrongEncounterUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = $this->buildFailingSut();
        $command = new UnassignPartyToEncounterCommand('encounterUlid', self::PARTY_ULID);
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongPartyUlid(): void
    {
        $this->expectException(WrongEncounterPartyUlidException::class);
        $sut = $this->buildFailingSut();
        $command = new UnassignPartyToEncounterCommand(self::ENCOUNTER_ULID, 'partyUlid');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterNotFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $sut = $this->buildFailingSut();
        $command = new UnassignPartyToEncounterCommand(self::ENCOUNTER_ULID, '01HWTXCCE8NQMVYNMFKJ3WEBY5');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionIfPartyCannotBeUnassigned(): void
    {
        $this->expectException(PartyNotAssignedToEncounterException::class);
        $party = new EncounterParty(self::PARTY_ULID, [1,2]);
        $encounter = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $sut = new UnassignPartyToEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UnassignPartyToEncounterCommand(self::ENCOUNTER_ULID, '01HWWC6GW0AYMRBNDP8H0N6KRW');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenEncounterCannotBeUpdated(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $party = new EncounterParty(self::PARTY_ULID, [1,2]);
        $encounter = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $sut = new UnassignPartyToEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new UnassignPartyToEncounterCommand(self::ENCOUNTER_ULID, self::PARTY_ULID);
        ($sut)($command);
    }

    /** @test */
    public function itShouldAssignPartyProperly(): void
    {
        $party = new EncounterParty(self::PARTY_ULID, [1,2]);
        $encounter = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $spy = new UpdateEncounterWriteModelSpy();
        $sut = new UnassignPartyToEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            $spy,
            new EventBusSpy()
        );
        $command = new UnassignPartyToEncounterCommand(self::ENCOUNTER_ULID, self::PARTY_ULID);
        ($sut)($command);
        $this->assertNotNull($spy->updatedEncounter);
    }

    /** @test */
    public function itShouldSendEvents(): void
    {
        $party = new EncounterParty(self::PARTY_ULID, [1,2]);
        $encounter = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $spy = new EventBusSpy();
        $sut = new UnassignPartyToEncounterCommandHandler(
            new EncounterRepositoryStub($encounter),
            new UpdateEncounterWriteModelSpy(),
            $spy
        );
        $command = new UnassignPartyToEncounterCommand(self::ENCOUNTER_ULID, self::PARTY_ULID);
        ($sut)($command);
        $this->assertCount(2, $spy->publishedEvents);
        $this->assertInstanceOf(PartyWasUnassigned::class, $spy->publishedEvents[0]);
        $this->assertInstanceOf(EncounterWasUpdated::class, $spy->publishedEvents[1]);
    }

    private function buildFailingSut(): UnassignPartyToEncounterCommandHandler
    {
        $sut = new UnassignPartyToEncounterCommandHandler(
            new EncounterRepositoryNotFoundStub(),
            new UpdateEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        return $sut;
    }
}
