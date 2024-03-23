<?php

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XpTracker\Character\Application\Command\AddCharacterToPartyCommand;
use XpTracker\Character\Infrastructure\Entrypoint\Api\PutAddCharacterToPartyController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class PutAddCharacterToPartyControllerTest extends TestCase
{
    public function testShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new PutAddCharacterToPartyController(new FailingJsonCommandBus());
        $content = 'asd';
        $request = Request::create('/some/url', 'PUT', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new PutAddCharacterToPartyController($spy);
        $content = '{"partyUlid":"partyUlid","characterUlid":"characterUlid"}';
        $request = Request::create('/some/url', 'PUT', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(AddCharacterToPartyCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('partyUlid', $command->partyUlid);
        $this->assertEquals('characterUlid', $command->characterUlid);
    }
}
