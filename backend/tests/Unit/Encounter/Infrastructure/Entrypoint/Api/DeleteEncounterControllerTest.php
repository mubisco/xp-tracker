<?php

namespace XpTracker\Tests\Unit\Encounter\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use XpTracker\Encounter\Application\Command\DeleteEncounterCommand;
use XpTracker\Encounter\Infrastructure\Entrypoint\Api\DeleteEncounterController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class DeleteEncounterControllerTest extends TestCase
{
    /** @test */
    public function itShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new DeleteEncounterController(new FailingJsonCommandBus());
        $response = ($sut)('01HX4AYA9GRCEKX07W3FZCD71Q');
        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function itShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new DeleteEncounterController($spy);
        $response = ($sut)('ulid');
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(DeleteEncounterCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('ulid', $command->encounterId);
    }
}
