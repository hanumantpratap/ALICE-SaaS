<?php
declare(strict_types=1);

namespace App\Actions\Person;

use App\Domain\Person\PersonNotFoundException;
use App\Exceptions\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class ListFrequentVisitorsAction extends PersonAction
{
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();

        $limit = (int) ($params["limit"] ?? 10);
        $threshold = (int) ($params["threshold"] ?? 3);

        $buildingId = (int) $this->token->building;

        try {
            $persons = $this->personRepository->getFrequentVisitors($threshold, $limit, $buildingId);

            $this->logger->info("All persons retrieved.");

            return $this->respondWithData($persons);
        } catch (PersonNotFoundException $ex) {
            throw new NotFoundException("No frequent visitors found");
        }
    }
}
