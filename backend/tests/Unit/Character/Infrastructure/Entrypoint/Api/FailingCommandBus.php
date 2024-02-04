<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

final class FailingCommandBus implements JsonCommandBus
{
    public function process($message, array $expectedExceptions): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_BAD_REQUEST);
    }
}
