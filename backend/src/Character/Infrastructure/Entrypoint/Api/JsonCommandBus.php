<?php

namespace XpTracker\Character\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

interface JsonCommandBus
{
    /**
     * @param array<string,int> $expectedExceptions
     */
    public function process(object $message, array $expectedExceptions): JsonResponse;
}
