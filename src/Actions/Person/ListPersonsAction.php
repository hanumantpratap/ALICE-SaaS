<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class ListPersonsAction extends PersonAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $persons = $this->personRepository->findAll();

        $this->logger->info("All persons retrieved.");

        return $this->respondWithData($persons);
    }
}
