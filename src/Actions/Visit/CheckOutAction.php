<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;

class CheckOutAction extends VisitAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();

        $visitId = (int) $this->resolveArg('id');
        $this->logger->info("Checking out visitId: " . $visitId);

        $visit = $this->visitRepository->findVisitOfId($visitId);
        $visit->checkedOutBy = $this->token->id;

        if (!isset($formData->checkOut)) {
          $visit->checkOut = new DateTime();
        } else {
          $visit->checkOut = new DateTime($formData->checkOut);
        }
        
        $this->visitRepository->save($visit);

        return $this->respondWithData($visit, 200);
    }
}

/**
 * @OA\Put(
 *     path="/visits/{visitId}/checkout",
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
