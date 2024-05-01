<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Event\ProjectEncounterWhenResolvedEventHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\Projection\EncounterProjectionException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\Projection\EncounterProjectionFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\Projection\EncounterProjectionSpy;

class ProjectEncounterWhenResolvedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $event = new EncounterWasSolved(
            encounterUlid: 'asd',
            partyUlid: '01HWV4RNZ0M5YPRRTN5Z7N8V7S',
            totalXp: 150
        );
        $sut = new ProjectEncounterWhenResolvedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $event = new EncounterWasSolved(
            encounterUlid: '01HWV4RPY89PJ1H3JS9267TNFH',
            partyUlid: '01HWV4RNZ0M5YPRRTN5Z7N8V7S',
            totalXp: 150
        );
        $sut = new ProjectEncounterWhenResolvedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(EncounterProjectionException::class);
        $event = new EncounterWasSolved(
            encounterUlid: '01HWV4RPY89PJ1H3JS9267TNFH',
            partyUlid: '01HWV4RNZ0M5YPRRTN5Z7N8V7S',
            totalXp: 150
        );
        $sut = new ProjectEncounterWhenResolvedEventHandler(
            new EncounterRepositoryStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new EncounterProjectionSpy();
        $event = new EncounterWasSolved(
            encounterUlid: '01HWV4RPY89PJ1H3JS9267TNFH',
            partyUlid: '01HWV4RNZ0M5YPRRTN5Z7N8V7S',
            totalXp: 150
        );
        $sut = new ProjectEncounterWhenResolvedEventHandler(
            new EncounterRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertNotNull($spy->itemToProject);
    }
}
