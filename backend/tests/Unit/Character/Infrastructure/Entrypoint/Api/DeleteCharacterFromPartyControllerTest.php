<?php

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use XpTracker\Character\Application\Command\DeleteCharacterFromPartyCommand;
use XpTracker\Character\Infrastructure\Entrypoint\Api\DeleteCharacterFromPartyController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class DeleteCharacterFromPartyControllerTest extends TestCase
{
    public function testShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new DeleteCharacterFromPartyController(new FailingJsonCommandBus());
        $response = ($sut)('partyUlid', 'characterUlid');
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new DeleteCharacterFromPartyController($spy);
        $response = ($sut)('partyUlid', 'characterUlid');
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(DeleteCharacterFromPartyCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('partyUlid', $command->partyUlid);
        $this->assertEquals('characterUlid', $command->characterUlid);
    }
}
