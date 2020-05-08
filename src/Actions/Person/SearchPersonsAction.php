<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class SearchPersonsAction extends PersonAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();
        $name = $params["name"] ?? "";
        $persons = $this->personRepository->findPersonsOfName($name);

        $this->logger->info("All persons retrieved.");

        return $this->respondWithData($persons);
    }
}
