<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\UpdateLevelsWhenCharacterWasRemovedEventHandler;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasDeleted;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingUpdateCharacterPartyWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\UpdateCharacterPartyWriteModelSpy;
use XpTracker\Tests\Unit\Encounter\Domain\BasicEncounterOM;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class UpdateLevelsWhenCharacterWasRemovedEventHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenPartyUlidWrong(): void
    {
        $this->expectException(WrongUlidValueException::class);
        $event = new EncounterWasDeleted('asd');
        $sut = new UpdateLevelsWhenCharacterWasRemovedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoCharactersFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $event = new EncounterWasDeleted('01HX1FTENRF6F49DD4X6ZGE2KZ');
        $sut = new UpdateLevelsWhenCharacterWasRemovedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenPartyNotFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $event = new EncounterWasDeleted('01HX1FTENRF6F49DD4X6ZGE2KZ');
        $encounter = BasicEncounterOM::aBuilder()->withRandomParty()->build();
        $sut = new UpdateLevelsWhenCharacterWasRemovedEventHandler(
            new EncounterRepositoryStub($encounter),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($event);
    }

    /** @test */
    public function itShouldSendProperEvent(): void
    {
        $spy = new EventBusSpy();
        $event = new EncounterWasDeleted('01HX1FTENRF6F49DD4X6ZGE2KZ');
        $encounter = BasicEncounterOM::aBuilder()->withRandomParty()->build();
        $sut = new UpdateLevelsWhenCharacterWasRemovedEventHandler(
            new EncounterRepositoryStub($encounter),
            new CharacterRepositoryStub(),
            new UpdateCharacterPartyWriteModelSpy(),
            $spy
        );
        ($sut)($event);
        $this->assertCount(1, $spy->publishedEvents);
        $this->assertInstanceOf(PartyLevelWasUpdated::class, $spy->publishedEvents[0]);
    }
}
