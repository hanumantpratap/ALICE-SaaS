<?php
declare(strict_types=1);

namespace App\Actions\Building;

use App\Domain\Building\Building;
use App\Domain\Building\SubBuilding;
use Psr\Http\Message\ResponseInterface as Response;

class CreateBuildingAction extends BuildingAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $building = new Building();
        $building->setMtid((int) $this->token->dist);

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

        $districtBuilding = $this->buildingRepository->findBuildingOfId((int) $this->token->dist);
        $subBuilding = new SubBuilding();
        $subBuilding->setChild($building);
        $districtBuilding->addSubBuilding($subBuilding);

        $this->buildingRepository->save($districtBuilding);

        return $this->respondWithData(null, 201);
    }
}

/**
 * @OA\Post(
 *     path="/buildings",
 *     tags={"buildings"},
 *     @OA\Parameter(
 *         name="Buildings",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="create building",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     )
 * )
 */
