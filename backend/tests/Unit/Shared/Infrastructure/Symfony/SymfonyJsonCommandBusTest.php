<?php

namespace XpTracker\Tests\Unit\Shared\Infrastructure\Symfony;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Character\Application\Command\AddCharacterCommand;
use XpTracker\Shared\Infrastructure\Symfony\SymfonyJsonCommandBus;

class SymfonyJsonCommandBusTest extends TestCase
{
    private SpyLoggerInterface $spyLoggerInterface;

    private const ALLOWED_EXCEPTIONS = [
        'InvalidArgumentException' => Response::HTTP_BAD_REQUEST,
        'DomainException' => Response::HTTP_NOT_FOUND,
    ];

    protected function setUp(): void
    {
        $this->spyLoggerInterface = new SpyLoggerInterface();
    }

    /** @test */
    public function itShouldReturnProperResponseCodeWhenExpectedError(): void
    {
        $sut = new SymfonyJsonCommandBus(
            new FailingMessageBusStub(new InvalidArgumentException()),
            $this->spyLoggerInterface
        );
        $result = $sut->process(
            new AddCharacterCommand('asd', 'asd', 1, 1),
            self::ALLOWED_EXCEPTIONS
        );
        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals('critical', $this->spyLoggerInterface->methodCalled);
    }

    /** @test */
    public function itShouldReturnn500ErrorWhenExceptionNotInList(): void
    {
        $sut = new SymfonyJsonCommandBus(
            new FailingMessageBusStub(new Exception()),
            $this->spyLoggerInterface
        );
        $result = $sut->process(
            new AddCharacterCommand('asd', 'asd', 1, 1),
            self::ALLOWED_EXCEPTIONS
        );
        $this->assertEquals(500, $result->getStatusCode());
        $this->assertEquals('critical', $this->spyLoggerInterface->methodCalled);
    }

    /** @test */
    public function itShouldReturnProperResponseCode(): void
    {
        $spyMessageBus = new SpyMessageBusStub();
        $sut = new SymfonyJsonCommandBus(
            $spyMessageBus,
            $this->spyLoggerInterface
        );
        $result = $sut->process(
            new AddCharacterCommand('asd', 'asd', 1, 1),
            self::ALLOWED_EXCEPTIONS
        );
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertInstanceOf(AddCharacterCommand::class, $spyMessageBus->message);
    }
}
