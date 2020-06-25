<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateVisitorTypeAction extends VisitorTypeAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $visitorTypeId = (int) $this->resolveArg('id');
        $visitorType = $this->visitorTypeRepository->findVisitorTypeOfId($visitorTypeId);

        $this->logger->info("Updating Visitor Type of id `${visitorTypeId}`");

        if (isset($formData->type)) {
            $visitorType->setType($formData->type);
        }

        if (isset($formData->inactive)) {
            $visitorType->setInactive($formData->inactive ? true : false );
        }

        $this->visitorTypeRepository->save($visitorType);

        return $this->respondWithData();
    }
}
/**
 * @OA\Put(
 *     path="/visitortype/{visitortypeId}",
 *     tags={"visitor-type"},
 *      @OA\Parameter(
 *         name="visitortypeId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update Visitor Type",
 *     ),
 *     @OA\RequestBody(
 *         description="Update Visitor Type",
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