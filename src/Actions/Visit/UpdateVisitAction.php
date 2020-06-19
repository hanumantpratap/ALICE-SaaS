<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateVisitAction extends VisitAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $visitId = (int) $this->resolveArg('id');
        $visit = $this->visitRepository->findVisitOfId($visitId);

        $this->logger->info("Updating Visit of id `${visitId}`");

        $reasonId = $formData->reasonId;
        $reason = $this->visitReasonRepository->findVisitReasonOfId($reasonId);
        $visit->setReason($reason);

        $visitorTypeId = $formData->visitorTypeId;
        $visitorType = $this->visitorTypeRepository->findVisitorTypeOfId($visitorTypeId);
        $visit->setVisitorType($visitorType);

        if (isset($formData->notes)) {
            $visit->setNotes($formData->notes);
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
 *              )
 *         ),
 *     )
 * )
 */
