<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Domain\Visit\VisitBadge;
use Psr\Http\Message\ResponseInterface as Response;

class AddVisitBadgeAction extends VisitAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
      $visitId = (int) $this->resolveArg("id");

      $badge = new VisitBadge();
      $badge->visitId = $visitId;

      $visit = $this->visitRepository->findVisitOfId($badge->visitId);
      $badge->setVisit($visit);
      $visit->addBadge($badge);

      $this->visitRepository->save($visit);

      $this->logger->info("Visit badge saved.");

    return $this->response->withStatus(201);
  }
}

/**
 * @OA\Post(
 *     path="/visits/{visitId}/badge",
 *     tags={"visits"},
 *     @OA\Parameter(
 *         name="visitId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Log a badge being printed",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={
 *                 "statusCode": 201,
 *                 "data": {}
 *             }
 *         )
 *     )
 * )
 */
