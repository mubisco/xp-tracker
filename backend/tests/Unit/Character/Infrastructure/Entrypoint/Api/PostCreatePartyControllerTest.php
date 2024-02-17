<?php

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XpTracker\Character\Application\Command\CreatePartyCommand;
use XpTracker\Character\Infrastructure\Entrypoint\Api\PostCreatePartyController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class PostCreatePartyControllerTest extends TestCase
{
    public function testShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new PostCreatePartyController(new FailingJsonCommandBus());
        $content = 'asd';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new PostCreatePartyController($spy);
        $content = '{"ulid":"ulid","name":"Chindasvinto"}';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(CreatePartyCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('ulid', $command->ulid);
        $this->assertEquals('Chindasvinto', $command->name);
    }
}
