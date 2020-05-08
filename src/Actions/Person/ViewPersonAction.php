<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

class ViewPersonAction extends PersonAction
{
    /**
     * {@inheritdoc}
     * OA\Get
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($userId);

        $this->logger->info("Person of id `${userId}` was viewed.");

        return $this->respondWithData($person);
    }
}
