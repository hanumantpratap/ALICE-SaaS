<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\Visit;
use App\Domain\Visit\VisitRepository;
use App\Domain\Person\PersonRepository;
use App\Exceptions;

class CreateVisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitRepository $visitRepository
     */

    public function __construct(LoggerInterface $logger, VisitRepository $visitRepository, PersonRepository $personRepository)
    {
        $this->visitRepository = $visitRepository;
        $this->personRepository = $personRepository;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $formData = $this->getFormData();
        $bad_fields = array();

        if (!isset($formData->personId)) {
            array_push($bad_fields, ['field' => 'personId', 'message' => 'You must provide a personId.']);
        }

        if (count($bad_fields) > 0) {
            throw new Exceptions\BadRequestException(null, $bad_fields);
        }

        $personId = (int) $formData->personId;
        $person = $this->personRepository->findPersonOfId($personId);

        $visit = new Visit();
        $visit->setPerson($person);

        $userId = (int) $this->token->id;
        $visit->setUserId($userId);

        if (isset($formData->notes)) {
            $visit->setNotes($formData->notes);
        }

        $this->visitRepository->save($visit);

        $newId = $visit->getId();

        $this->logger->info("Visit of id `${newId}` was created.");

        $newVisit = $this->visitRepository->findVisitOfId($newId);

        return $this->respondWithData($newVisit);
    }
}

/**
 * @OA\Post(
 *     path="/visits",
 *     tags={"visits"},
 *     @OA\Response(
 *         response=200,
 *         description="Create Visitor",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={"statusCode": 200, 
 *                      "data": {
 *                            "id": 2,
 *                            "userId": 1,
 *                            "notes": "Lauren Admin",
 *                            "visitor": {
 *                                 "personId": 3185,
 *                                 "firstName": "Rosalinda",
 *                                 "lastName": "Walt",
 *                                 "emailAddress": "Rosalinda.Walt@laureninnovations.com"
 *                            }
 *                       }}
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={
 *                  "personId": 3185,
 *                  "notes": "hello"
 *            }
 *         )
 *     )
 * )
 */
