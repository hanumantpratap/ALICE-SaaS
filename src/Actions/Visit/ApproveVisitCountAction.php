<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Infrastructure\Persistence\User;
use Slim\Exception\HttpBadRequestException;

use Psr\Http\Message\ResponseInterface as Response;

class ApproveVisitCountAction extends VisitAction
{
    protected function action(): Response
    {
        $filterData= [] ;       
        $this->logger->info("BuildingId " . $this->resolveArg('building_id'));

        $buildingId = (int) $this->resolveArg('building_id');        
        $building = $this->buildingRepository->findBuildingOfId($buildingId);
        $this->logger->info("Approving visit of building_id `${buildingId}`");

        if (!isset($building->id)) throw new HttpBadRequestException( $this->request, "Wrong Parameter, building_id" );
        $filterData['building_id'] = $buildingId;
        if($this->request->get('start_date')){
            $filterData['start_date'] = $this->request->get('start_date');
        }
        if($this->request->get('end_date')){
            $filterData['end_date'] = $this->request->get('end_date');
        }
        if($this->request->get('start_date') > $this->request->get('end_date')){
            throw new HttpBadRequestException( $this->request, "start_date could be smaller that end_date" );
        }
        $totalCount = $this->visitRepository->findAllowedVisitOfBuildingId($filterData);
        return $this->respondWithData($totalCount);
    }
}

/**
 * @OA\GET(
 *     path="/visits/{buildingId}/approveVisit",
 *     tags={"visits"},
 *      @OA\Parameter(
 *         name="buildingId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Approve Visit",
 *     ),
 *     @OA\RequestBody(
 *         description="Approve Visit",
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