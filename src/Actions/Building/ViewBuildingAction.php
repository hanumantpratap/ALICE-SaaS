<?php
declare(strict_types=1);

namespace App\Actions\Building;

use Psr\Http\Message\ResponseInterface as Response;

class ViewBuildingAction extends BuildingAction
{
    protected function action(): Response
    {
        $buildingId = $this->resolveArg('id');
        $buildingId = $buildingId == "selected" ? $this->token->building : (int) $buildingId; // allow for /buildings/selected

        $building = $this->buildingRepository->findBuildingOfId($buildingId);
        
        return $this->respondWithData(['building' => $building]);
    }

    /**
     * @OA\Get(
     *     path="/buildings/{buildingId}",
     *     tags={"buildings"},
     *      @OA\Response(
     *         response=200,
     *         description="View Building",
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
     *                      }
     *                  }
     *         )
     *     )
     * )
     */

    /**
     * @OA\Get(
     *     path="/buildings/selected",
     *     tags={"buildings"},
     *      @OA\Response(
     *         response=200,
     *         description="View Selected Building",
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
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}
