<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Event;

use Doctrine\DBAL\Connection;
use DomainException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use XpTracker\Encounter\Application\Event\UpdateEncountersWhenPartyLevelWasUpdatedEventHandler;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

class UpdateEncountersWhenPartyLevelWasUpdatedEventHandlerTest extends TestCase
{
    private MessageBusInterface&MockObject $messageBus;
    private Connection&MockObject $connection;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->connection = $this->createMock(Connection::class);
    }

    /** @test */
    public function itShouldThrowExceptionWhenPartyUlidWrong(): void
    {
        $this->expectException(WrongUlidValueException::class);
        $event = new PartyLevelWasUpdated('01HX22C4Q89AM44GXBNQNT8D1W', 'asd', [1,2]);
        $sut = new UpdateEncountersWhenPartyLevelWasUpdatedEventHandler($this->messageBus, $this->connection);
        ($sut)($event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCannotSendThroughBus(): void
    {
        $this->expectException(DomainException::class);
        $expectedValues = [['encounter_id' => '01HX22KNY0VTPNANYSYQ29MEWR']];
        $this->connection->method('fetchAllAssociative')->willReturn($expectedValues);
        $this->messageBus->method('dispatch')->willThrowException(new DomainException());
        $event = new PartyLevelWasUpdated('01HX22C4Q89AM44GXBNQNT8D1W', '01HX22MZXR0WNY4JT9KVNRYSA1', [1,2]);
        $sut = new UpdateEncountersWhenPartyLevelWasUpdatedEventHandler($this->messageBus, $this->connection);
        ($sut)($event);
    }
}
