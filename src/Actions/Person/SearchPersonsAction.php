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
        $persons = $this->personRepository->findPersonsByParams($params);
 
        $this->logger->info("SearchPersonAction::action() retrieved the records." );

        return $this->respondWithData($persons);
    }
}
