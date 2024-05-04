<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\AddExperiencePointsWhenEncounterWasResolvedEventHandler;
use XpTracker\Character\Domain\AddCharacterWriteModelException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Encounter\Domain\Party\PartyWasUpdated;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingUpdateCharacterPartyWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\UpdateCharacterPartyWriteModelSpy;
use XpTracker\Tests\Unit\Custom\IsImmediateDate;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class AddExperiencePointsWhenEncounterWasResolvedEventHandlerTest extends TestCase
{
    private IsImmediateDate $assertion;

    protected function setUp(): void
    {
        $this->assertion = new IsImmediateDate();
    }

    /** @test */
    public function itShouldThrowExceptionWhenPartyUlidWrong(): void
    {
        $this->expectException(WrongUlidValueException::class);
        $event = new EncounterWasSolved('01HX1FTENRF6F49DD4X6ZGE2KZ', 'asd', 0);
        $sut = new AddExperiencePointsWhenEncounterWasResolvedEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoCharactersFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $event = new EncounterWasSolved('01HX1FTENRF6F49DD4X6ZGE2KZ', '01HX1J0FWGZ91P77YDES05J57Z', 0);
        $sut = new AddExperiencePointsWhenEncounterWasResolvedEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterCannotBeUpdated(): void
    {
        $this->expectException(AddCharacterWriteModelException::class);
        $event = new EncounterWasSolved('01HX1FTENRF6F49DD4X6ZGE2KZ', '01HX1J0FWGZ91P77YDES05J57Z', 0);
        $sut = new AddExperiencePointsWhenEncounterWasResolvedEventHandler(
            new CharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($event);
    }

    /** @test */
    public function itShouldUpdateCharacterProperly(): void
    {
        $spy = new UpdateCharacterPartyWriteModelSpy();
        $event = new EncounterWasSolved('01HX1FTENRF6F49DD4X6ZGE2KZ', '01HX1J0FWGZ91P77YDES05J57Z', 6000);
        $sut = new AddExperiencePointsWhenEncounterWasResolvedEventHandler(
            new CharacterRepositoryStub(),
            $spy,
            new EventBusSpy()
        );
        ($sut)($event);
        $this->assertNotNull($spy->updatedCharacter);
        $this->assertEquals(3, $spy->timesCalled);
        $rawData = $spy->updatedCharacter->toJson();
        $data = json_decode($rawData);
        $this->assertEquals(2000, $data->xp);
    }

    /** @test */
    public function itShouldSendProperEvent(): void
    {
        $event = new EncounterWasSolved('01HX1FTENRF6F49DD4X6ZGE2KZ', '01HX1J0FWGZ91P77YDES05J57Z', 6000);
        $spy = new EventBusSpy();
        $sut = new AddExperiencePointsWhenEncounterWasResolvedEventHandler(
            new CharacterRepositoryStub(),
            new UpdateCharacterPartyWriteModelSpy(),
            $spy
        );
        ($sut)($event);
        $this->assertCount(1, $spy->publishedEvents);
        $this->assertInstanceOf(PartyLevelWasUpdated::class, $spy->publishedEvents[0]);
        $partyUpdatedEvent = $spy->publishedEvents[0];
        $this->assertEquals('01HX1FTENRF6F49DD4X6ZGE2KZ', $partyUpdatedEvent->encounterUlid);
        $this->assertEquals('01HX1FTENRF6F49DD4X6ZGE2KZ', $partyUpdatedEvent->id());
        $this->assertThat($partyUpdatedEvent->occurredOn(), $this->assertion);
        $this->assertEquals('01HX1J0FWGZ91P77YDES05J57Z', $partyUpdatedEvent->partyUlid);
        $this->assertEquals([3, 3, 3], $partyUpdatedEvent->charactersLevel);
    }
}
