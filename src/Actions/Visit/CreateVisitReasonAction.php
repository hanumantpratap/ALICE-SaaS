<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Domain\Visit\VisitReason;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;


class CreateVisitReasonAction extends VisitReasonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $formData = $this->getFormData();
    $this->logger->info('post new visit reason', (array) $formData);

    if (!isset($formData->type))  {
        throw new Exceptions\BadRequestException('Missing parameter, type.');
    }

    $visitReason = new VisitReason();
    $visitReason->setType( $formData->type );

    $this->visitReasonRepository->save( $visitReason );

    $this->logger->info("New Visit Reason saved.");
    return $this->respondWithData(['message' => 'Visit Reason created', 'id' => $visitReason->id], 201);
  }
}
/**
 * @OA\Post(
 *     path="/visit-reasons",
 *     tags={"visit-reason"},
 *     @OA\Response(
 *         response=200,
 *         description="New Visit Reason",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={"statusCode": 200, 
 *                      "data": {
 *                          
 *                       }}
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Create new Visit Reason",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  
 *              )
 *         ),
 *     )
 * )
 */
