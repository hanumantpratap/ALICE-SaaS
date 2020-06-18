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

        $persons = $this->personRepository->getFrequentVisitors($threshold, $limit);

        $this->logger->info("All persons retrieved.");

        return $this->respondWithData($persons);
    }
}
