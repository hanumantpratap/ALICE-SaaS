<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ListVisitReasonsAction extends VisitReasonAction
{
    protected function action(): Response
    {
        $visitReasons = $this->visitReasonRepository->findAll();

        $this->logger->info("Visit Reason list was viewed.");
        
        return $this->respondWithData($visitReasons);
    }

    /**
     * @OA\Get(
     *     path="/visit-reasons",
     *     tags={"visit-reason"},
     *     @OA\Parameter(
     *         name="visitor_name",
     *         in="query",
     *         description="Filter by visitor",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="View Visit Reason",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}