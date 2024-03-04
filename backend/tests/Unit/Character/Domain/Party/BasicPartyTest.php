<?php

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\PartyWasCreated;

class BasicPartyTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        BasicParty::create('1HPWQC2NFH8Y8Z1HG6BARZGNH', 'Comando G');
    }

    public function testItShouldReturnProperData(): void
    {
        $sut = BasicParty::create('01HPWV7N1NEJK77X40JZCHBRMH', 'Equipo A');
        $this->assertEquals('01HPWV7N1NEJK77X40JZCHBRMH', $sut->id());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(PartyWasCreated::class, $event);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredOn());
        $this->assertEquals('01HPWV7N1NEJK77X40JZCHBRMH', $event->id());
        $this->assertEquals('Equipo A', $event->partyName);
        $expectedJson = '{"name":"Equipo A"}';
        $this->assertEquals($expectedJson, $sut->toJson());
    }
}
