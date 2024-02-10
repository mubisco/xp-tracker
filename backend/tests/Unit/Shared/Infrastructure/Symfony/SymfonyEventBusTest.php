<?php

namespace XpTracker\Tests\Unit\Shared\Infrastructure\Symfony;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use XpTracker\Shared\Infrastructure\Symfony\SymfonyEventBus;
use XpTracker\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyEventBusTest extends TestCase
{
    private MessageBusInterface|MockObject $mockEventAsyncBus;
    private SymfonyEventBus $sut;

    protected function setUp(): void
    {
        $this->mockEventAsyncBus = $this->createMock(originalClassName: MessageBusInterface::class);
        $this->sut = new SymfonyEventBus(
            eventAsyncBus: $this->mockEventAsyncBus,
        );
    }

    public function testItShouldThrowInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->sut->publish(['asdf']);
    }

    public function testItShouldSendEventThrougBuses(): void
    {
        $mockedEvent = $this->createMock(DomainEvent::class);
        $this->mockEventAsyncBus->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope($mockedEvent));
        $this->sut->publish([$mockedEvent]);
    }
}
