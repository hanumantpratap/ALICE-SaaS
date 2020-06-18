<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class ListCurrentVisitorsAction extends PersonAction
{
    protected function action(): Response
    {
        $buildingId = (int) $this->token->building;

        $persons = $this->personRepository->getCurrentVisitors($buildingId);

        $this->logger->info("All persons retrieved.");

        return $this->respondWithData($persons);
    }
}
