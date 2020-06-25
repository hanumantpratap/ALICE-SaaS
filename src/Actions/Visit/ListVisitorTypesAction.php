<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ListVisitorTypesAction extends VisitorTypeAction
{
    protected function action(): Response
    {
        $visitorTypes = $this->visitorTypeRepository->findAll();

        $this->logger->info("Visitor Type list was viewed.");
        
        return $this->respondWithData($visitorTypes);
    }
  
    /**
     * @OA\Get(
     *     path="/visitortype",
     *     tags={"visitor-type"},
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
     *         description="View Visitor Type",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          
     *                          
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}