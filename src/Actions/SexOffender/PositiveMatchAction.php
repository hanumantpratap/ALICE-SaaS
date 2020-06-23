<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use App\Domain\SexOffender\SexOffenderMatch;
use Psr\Http\Message\ResponseInterface as Response;

class PositiveMatchAction extends SexOffenderAction
{
    protected function action(): Response
    {
        $personId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($personId);

        $formData = $this->getFormData();
        $offenderId = $formData->offenderId;

        $this->logger->info("Match Person `${personId} with Offender `${offenderId}`");

        $sexOffender = $this->sexOffenderRepository->findSexOffenderOfId($offenderId);
        $sexOffenderMatch = new SexOffenderMatch();
        $sexOffenderMatch->setSexOffender($sexOffender);
        $sexOffenderMatch->setEnteredBy((int) $this->token->id);

        $person->setSexOffenderMatch($sexOffenderMatch);
        $this->personRepository->save($person);
        
        return $this->respondWithData(null, 201);
    }

     /**
      * @OA\Put(
      *     path="/people/{personId}/sex-offender/match",
      *     tags={"people", "sex-offenders"},
      *     @OA\Response(
      *         response=201,
      *         description="Create a match between a person and a sex offender",
      *         @OA\MediaType(
      *             mediaType="application/json"
      *         )
      *     ),
      *     @OA\RequestBody()
      * )
      */
}
