<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route("/api/party", name: "api_party_all", methods: ['GET'])]
final class GetAllPartiesController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_OK);
    }
}
