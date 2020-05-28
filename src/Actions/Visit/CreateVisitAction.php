<?php
declare(strict_types=1);
namespace App\Actions\Visit;
use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\Visit;
use App\Domain\Visit\VisitRepository;
use App\Domain\Person\Person;
use App\Domain\Person\PersonName;
use App\Domain\Person\PersonEmail;
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
        if (!isset($formData->personId) && (!isset($formData->firstName) || !isset($formData->lastName) )) {
            throw new Exceptions\BadRequestException('Please provide a person ID or information for creating a new visitor.');
        }
        if (isset($formData->personId)) {
            $person = $this->personRepository->findPersonOfId((int) $formData->personId);
        }
        else {
            $person = new Person();
            $person->setStatus(1);
            $name = $person->getName();
            $name->setGivenName($formData->firstName);
            $name->setFamilyName($formData->lastName);
            $person->setName($name);
            if( isset($formData->picture)) {
                $visitorSettings = $person->getVisitorSettings();
                $visitorSettings->setPicture($formData->picture);
            }
            $this->personRepository->save($person);
            // The reason this has to be done as a separate save call is because 
            // the person ID needs to be set to the email's source column.
            // I don't yet know what the point of the column is.
            if (isset($formData->email)) {
                $email = new PersonEmail();
                $email->setEmailAddress($formData->email);
                $email->setPerson($person);
                $email->setSource($person->getPersonId());
                $person->setEmail($email);
                $this->personRepository->save($person);
            }
        }
        
        $visit = new Visit();
        $visit->setPerson($person);
        $visit->setBuildingId((int) $this->token->building);
        $visit->setUserId((int) $this->token->id);

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