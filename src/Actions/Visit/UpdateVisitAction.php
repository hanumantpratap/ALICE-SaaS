<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use DateTime;
use App\Exceptions\ForbiddenException;

class UpdateVisitAction extends VisitAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $visitId = (int) $this->resolveArg('id');
        $visit = $this->visitRepository->findVisitOfId($visitId);
        $person = $visit->getPerson();

        $this->logger->info("Updating Visit of id `${visitId}`");

        if (!$visit->checkIn) {
            if ($person->getSexOffenderMatch() !== null && !$visit->getApproved()) {
                if (isset($formData->approvedBy)) {
                    $user = $this->userRepository->findUserOfId((int) $formData->approvedBy);
                    $visit->setApprovedByUser( $user );
                    $visit->setApproved(true);
                }
                else {
                    throw new ForbiddenException("Visitor cannot be checked in without approval.");
                }
            }

            $visit->setCheckIn(new DateTime()); //check in if not already
        }

        $reasonId = $formData->reasonId;
        $reason = $this->visitReasonRepository->findVisitReasonOfId($reasonId);
        $visit->setReason($reason);

        $visitorTypeId = $formData->visitorTypeId;
        $visitorType = $this->visitorTypeRepository->findVisitorTypeOfId($visitorTypeId);
        $visit->setVisitorType($visitorType);

        if (isset($formData->notes)) {
            $visit->setNotes($formData->notes);
        }

        //if (isset($formData->visiting) && is_array($formData->visiting)) {
        if (isset($formData->visiting)) {
            $visiting = $formData->visiting;
            $visit->clearVisiting();
            $this->visitRepository->save($visit);

            if ($visiting->type == "person") {
                $person = $this->personRepository->findPersonOfId((int) $visiting->id);
                $visit->addPersonToVisit($person);
            }
            else {
                $student = $this->studentRepository->findStudentOfId((int) $visiting->id);
                $visit->addStudentToVisit($student);
            }
        }
        else {
            $visit->clearVisiting();
        }

        if (!$visit->checkIn) {
            $visit->setCheckIn(new DateTime()); //check in if not already
        }

        $this->visitRepository->save($visit);

        return $this->respondWithData();
    }
}

/**
 * @OA\Put(
 *     path="/visits/{visitId}",
 *     tags={"visits"},
 *      @OA\Parameter(
 *         name="visitId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update Visit",
 *     ),
 *     @OA\RequestBody(
 *         description="Update Visit",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(
 *                     property="reason",
 *                     description="Visit reason",
 *                     type="string"
 *                 ),
 *                  @OA\Property(
 *                     property="visitorType",
 *                     description="Type of visitor",
 *                     type="string"
 *                 ),
 *                  @OA\Property(
 *                     property="notes",
 *                     description="Visit notes.",
 *                     type="string"
 *                 ),
 *                  @OA\Property(
 *                     property="visiting",
 *                     description="Who visitor is visiting.",
 *                     type="object"
 *                 ),
 *              )
 *         ),
 *     )
 * )
 */
