<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\SexOffender\SexOffenderNonMatch;

class NonMatchesAction extends SexOffenderAction
{
    protected function action(): Response
    {
        $personId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($personId);

        $formData = $this->getFormData();
        $offenders = $formData->offenders;

        $this->logger->info("Match Person `${personId} with Offender `" . json_encode($offenders) . "`");

        foreach ($offenders as $offenderId) {
            $sexOffender = $this->sexOffenderRepository->findSexOffenderOfId($offenderId);
            $sexOffenderNonMatch = new SexOffenderNonMatch();
            $sexOffenderNonMatch->setSexOffender($sexOffender);
            $sexOffenderNonMatch->setEnteredBy((int) $this->token->id);
            $person->addSexOffenderNonMatch($sexOffenderNonMatch);
        }

        $this->personRepository->save($person);
        
        return $this->respondWithData(null, 201);
    }

     /**
      * @OA\Post(
      *     path="/people/{personId}/sex-offender/nonMatches",
      *     tags={"people", "sex-offenders"},
      *     @OA\Response(
      *         response=201,
      *         description="Add Non-Matches to a Person",
      *         @OA\MediaType(
      *             mediaType="application/json"
      *         )
      *     ),
      *     @OA\RequestBody()
      * )
      */
}
