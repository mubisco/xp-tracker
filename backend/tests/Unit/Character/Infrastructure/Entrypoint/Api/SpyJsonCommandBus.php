<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

final class SpyJsonCommandBus implements JsonCommandBus
{
    public ?object $message = null;

    public function process($message, array $expectedExceptions): JsonResponse
    {
        $this->message = $message;
        return new JsonResponse([], Response::HTTP_OK);
    }
}
