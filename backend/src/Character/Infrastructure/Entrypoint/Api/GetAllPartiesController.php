<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Character\Application\Query\FindAllPartiesQuery;
use XpTracker\Character\Application\Query\FindAllPartiesQueryHandler;

#[AsController]
#[Route("/api/party", name: "api_party_all", methods: ['GET'])]
final class GetAllPartiesController
{
    public function __construct(private readonly FindAllPartiesQueryHandler $useCase)
    {
    }
    public function __invoke(): JsonResponse
    {
        $query = new FindAllPartiesQuery();
        $results = ($this->useCase)($query);
        return new JsonResponse($results, Response::HTTP_OK);
    }
}
