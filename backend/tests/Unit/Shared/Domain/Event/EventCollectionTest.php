<?php

namespace XpTracker\Tests\Unit\Shared\Domain\Event;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;

class EventCollectionTest extends TestCase
{
    public function testItShouldThrowExceptionIfIdNotAnUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EventCollection('asd', []);
    }

    public function testItShouldThrowExceptionWhenEventsNotDomainEvents(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EventCollection('01HR5GJ3Q5HN07S9APSVZSYXRX', ['asd', 'asd']);
    }

    public function testItShouldReturnProperValues(): void
    {
        $events = [new PartyWasCreated('01HR5H6VNHF85AXXFY3Z268R04', 'asd')];
        $sut = new EventCollection('01HR5GJ3Q5HN07S9APSVZSYXRX', $events);
        $this->assertEquals('01HR5GJ3Q5HN07S9APSVZSYXRX', $sut->ulid());
        $this->assertSame($events, $sut->events());
    }
}
