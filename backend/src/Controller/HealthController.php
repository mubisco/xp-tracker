<?php

declare(strict_types=1);

namespace XpTracker\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/public/health", name: "api_health", methods: "GET")]
final class HealthController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['ready' => true], Response::HTTP_OK);
    }
}
