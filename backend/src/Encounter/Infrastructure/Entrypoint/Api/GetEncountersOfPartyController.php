<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Encounter\Application\Query\EncountersByPartyUlidQuery;
use XpTracker\Encounter\Application\Query\EncountersByPartyUlidQueryHandler;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;

#[AsController]
#[Route("/api/encounter/{partyUlid}", name: "api_encounter_by_party", methods: "GET")]
final class GetEncountersOfPartyController
{
    public function __construct(private readonly EncountersByPartyUlidQueryHandler $useCase)
    {
    }

    public function __invoke(string $partyUlid): JsonResponse
    {
        try {
            $query = new EncountersByPartyUlidQuery($partyUlid);
            $encounters = ($this->useCase)($query);
            return new JsonResponse($encounters, Response::HTTP_OK);
        } catch (WrongEncounterPartyUlidException) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        } catch (PartyNotFoundException) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }
    }
}
