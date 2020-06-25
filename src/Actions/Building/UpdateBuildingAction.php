<?php
declare(strict_types=1);

namespace App\Actions\Building;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateBuildingAction extends BuildingAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Update Building Id " . $this->resolveArg('id'));

        /* $argument = $this->resolveArg('id');
        $buildingId = $argument == 'districtBuilding' ? (int) $this->token->dist : (int) $argument; */
        
        $buildingId = (int) $this->resolveArg('id');
        $building = $this->buildingRepository->findBuildingOfId($buildingId);

        if (isset($formData->name)) {
            $building->setName($formData->name);
        }

        if (isset($formData->address)) {
            $building->setAddress($formData->address);
        }

        if (isset($formData->city)) {
            $building->setCity($formData->city);
        }

        if (isset($formData->state)) {
            $building->setState($formData->state);
        }

        if (isset($formData->zip)) {
            $building->setZip($formData->zip);
        }

        if (isset($formData->county)) {
            $building->setCounty($formData->county);
        }

        $this->buildingRepository->save($building);

        return $this->respondWithData(null, 200);
    }
/**
 * @OA\Put(
 *     path="/buildings/{buildingsId}",
 *     tags={"buildings"},
 *     @OA\Response(
 *         response=200,
 *         description="Update building",
 *     )
 * )
 */