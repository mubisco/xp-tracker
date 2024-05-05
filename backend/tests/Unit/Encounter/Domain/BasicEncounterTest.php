<?php

namespace XpTracker\Tests\Unit\Encounter\Domain;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\EncounterNotResolvableException;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\EncounterWasDeleted;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\EncounterWasUpdated;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Monster\MonsterWasRemoved;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\PartyAlreadyAssignedException;
use XpTracker\Encounter\Domain\Party\PartyNotAssignedToEncounterException;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;
use XpTracker\Encounter\Domain\Party\PartyWasUnassigned;
use XpTracker\Encounter\Domain\Party\PartyWasUpdated;
use XpTracker\Encounter\Domain\WrongEncounterNameException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Tests\Unit\Custom\IsImmediateDate;

class BasicEncounterTest extends TestCase
{
    private IsImmediateDate $assertion;

    protected function setUp(): void
    {
        $this->assertion = new IsImmediateDate();
    }
    /** @test */
    public function itShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        BasicEncounter::create('asd', 'Test Encounter');
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongName(): void
    {
        $this->expectException(WrongEncounterNameException::class);
        BasicEncounter::create('01HTTPV1VG40BB2B9NX6KKJZ50', 'Test;DROP TABLE users');
    }

    /** @test */
    public function itShouldCreateInstanceProperly(): void
    {
        $sut = BasicEncounter::create('01HTTPV1VG40BB2B9NX6KKJZ50', 'Test');
        $this->assertEquals('01HTTPV1VG40BB2B9NX6KKJZ50', $sut->id());
        $expectedValues = [
            'level' => 'UNASSIGNED',
            'name' => 'Test',
            'status' => 'OPEN',
            'party' => '',
            'totalXp' => 0,
            'totalCrPoints' => 0,
            'monsters' => []
        ];
        $this->assertEquals($expectedValues, $sut->collect());
        $this->assertEquals('', $sut->partyId());
        $this->assertEquals('OPEN', $sut->status());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(EncounterWasCreated::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldAddMonsterProperly(): void
    {
        $sut = BasicEncounterOM::aBuilder()->build();
        $monster = EncounterMonster::fromStringValues('Orc', '1/2');
        $sut->addMonster($monster);
        $this->assertEquals(1, $sut->monsterQuantity());
        $expectedMonsters = [['name' => 'Orc', 'challengeRating' => '1/2', 'experiencePoints' => 100]];
        $data = $sut->collect();
        $this->assertEquals($expectedMonsters, $data['monsters']);
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(MonsterWasAdded::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertInstanceOf(EncounterWasUpdated::class, $events[1]);
        $this->assertThat($events[1]->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldRemoveMonsterProperly(): void
    {
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->build();
        $sut->removeMonster($orc);
        $this->assertEquals(1, $sut->monsterQuantity());
        $data = $sut->collect();
        $this->assertEquals(
            [['name' => 'Kobold', 'challengeRating' => '1/2', 'experiencePoints' => 100]],
            $data['monsters']
        );
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(MonsterWasRemoved::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertInstanceOf(EncounterWasUpdated::class, $events[1]);
        $this->assertThat($events[1]->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldApplyPartyProperly(): void
    {
        $sut = BasicEncounterOM::aBuilder()->build();
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut->assignToParty($party);
        $data = $sut->collect();
        $this->assertEquals('EMPTY', $data['level']);
        $this->assertEquals('01HWTEG560RMHQ52KC5SGGPCEJ', $sut->partyId());
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(PartyWasAssigned::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertInstanceOf(EncounterWasUpdated::class, $events[1]);
        $this->assertThat($events[1]->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldApplyPartyProperlyWhenMonstersAdded(): void
    {
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->build();
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut->assignToParty($party);
        $data = $sut->collect();
        $this->assertEquals('DEADLY', $data['level']);
    }

    /** @test */
    public function itShouldThrowExceptionWhenApplyingAPartyOnAAlreadyApplyedEncounter(): void
    {
        $this->expectException(PartyAlreadyAssignedException::class);
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $anotherParty = new EncounterParty('01HWTHEWE01T7BBSYE0331TR85', [1,2]);
        $sut->assignToParty($anotherParty);
    }

    /** @test */
    public function itShouldUpdateLevelWhenMonsterAddedAfterPartyAssigned(): void
    {
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $sut->addMonster($kobold);
        $data = $sut->collect();
        $this->assertEquals('EASY', $data['level']);
    }

    /** @test */
    public function itShouldUnassignPartyProperly(): void
    {
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->withParty($party)->build();
        $sut->unassign(SharedUlid::fromString('01HWTEG560RMHQ52KC5SGGPCEJ'));
        $data = $sut->collect();
        $this->assertEquals('UNASSIGNED', $data['level']);
        $this->assertEquals('', $sut->partyId());
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(PartyWasUnassigned::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertInstanceOf(EncounterWasUpdated::class, $events[1]);
        $this->assertThat($events[1]->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldThrowExceptionWhenTryingToUnassignAWrongParty(): void
    {
        $this->expectException(PartyNotAssignedToEncounterException::class);
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut = BasicEncounterOM::aBuilder()->withParty($party)->build();
        $anotherPartyUlid = SharedUlid::fromString('01HWTHEWE01T7BBSYE0331TR85');
        $sut->unassign($anotherPartyUlid);
    }

    /** @test */
    public function itShouldThrowExceptionWhenTryingToUnassignAUnassignedEncounter(): void
    {
        $this->expectException(PartyNotAssignedToEncounterException::class);
        $sut = BasicEncounterOM::aBuilder()->build();
        $anotherPartyUlid = SharedUlid::fromString('01HWTHEWE01T7BBSYE0331TR85');
        $sut->unassign($anotherPartyUlid);
    }

    /** @test */
    public function itShouldThrowExceptionTryingToUpdateAPartyOnAUnassignedEncounter(): void
    {
        $this->expectException(PartyNotAssignedToEncounterException::class);
        $sut = BasicEncounterOM::aBuilder()->build();
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut->updateAssignedParty($party);
    }

    /** @test */
    public function itShouldThrowExceptionWhenTryingToUpdateAPartyNotAssigned(): void
    {
        $this->expectException(PartyNotAssignedToEncounterException::class);
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->withParty($party)->build();
        $party = new EncounterParty('01HWTJBGEG031TP9XZKC8WHTT1', [2,2]);
        $sut->updateAssignedParty($party);
    }

    /** @test */
    public function itShouldUpdatePartyProperly(): void
    {
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->withParty($party)->build();
        $updatedParty = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [2,2]);
        $sut->updateAssignedParty($updatedParty);
        $data = $sut->collect();
        $this->assertEquals('HARD', $data['level']);
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(PartyWasUpdated::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertInstanceOf(EncounterWasUpdated::class, $events[1]);
        $this->assertThat($events[1]->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldResolveAnEncounter(): void
    {
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->withParty($party)->build();
        $sut->resolve();
        $this->assertEquals('RESOLVED', $sut->status());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(EncounterWasSolved::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $event = $sut->pullEvents()[0];
        $this->assertEquals(200, $event->totalXp);
    }

    /** @test */
    public function itShouldThrowExceptionWhenTryingToResolveAnEncounterWithoutParty(): void
    {
        $this->expectException(EncounterNotResolvableException::class);
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->build();
        $sut->resolve();
    }

    /** @test */
    public function itShouldBeDeleteProperly(): void
    {
        $sut = BasicEncounterOM::aBuilder()->build();
        $sut->delete();
        $this->assertEquals('DELETED', $sut->status());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(EncounterWasDeleted::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertEquals($sut->id(), $events[0]->id());
    }
}
