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
     *         description="View Buildings"
     *     )
     * )
     */
}
