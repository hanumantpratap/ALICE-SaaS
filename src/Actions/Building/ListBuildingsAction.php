<?php
declare(strict_types=1);

namespace App\Actions\Building;

use Psr\Http\Message\ResponseInterface as Response;

class ListBuildingsAction extends BuildingAction
{
    protected function action(): Response
    {
        $buildings = $this->buildingRepository->findActiveBuildings();

        $this->logger->info("Buildings list was viewed.");
        
        return $this->respondWithData($buildings);
    }

    /**
     * @OA\Get(
     *     path="/buildings",
     *     tags={"buildings"},
     *      @OA\Response(
     *         response=200,
     *         description="View Buildings",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                         {
     *                              "id": 3001,
     *                              "name": "NaviGate360 Kindergarten",
     *                              "address": "123 Sample Address",
     *                              "city": "Cleveland",
     *                              "state": "Ohio",
     *                              "zip": "44114 ",
     *                              "county": null,
     *                              "active": true
     *                          },
     *                          {
     *                              "id": 3002,
     *                              "name": "NaviGate360 Elementary",
     *                              "address": "123 Sample Address",
     *                              "city": "Cleveland",
     *                              "state": "Ohio",
     *                              "zip": "44114 ",
     *                              "county": null,
     *                              "active": true
     *                          },
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}
