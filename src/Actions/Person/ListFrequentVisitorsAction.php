<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class ListFrequentVisitorsAction extends PersonAction
{
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();

        $limit = (int) ($params["limit"] ?? 10);
        $threshold = (int) ($params["threshold"] ?? 3);

        $buildingId = (int) $this->token->building;

        $persons = $this->personRepository->getFrequentVisitors($threshold, $limit, $buildingId);

        $this->logger->info("All persons retrieved.");

        return $this->respondWithData($persons);
    }
}
