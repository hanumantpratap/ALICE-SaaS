<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class CreateVisitAction extends Action
{
    protected function action(): Response
    {
        $formData = $this->getFormData();

        return $this->respondWithData($formData);
    }
}

/**
 * @OA\Post(
 *     path="/visits",
 *     tags={"visits"},
 *     @OA\Response(
 *         response=200,
 *         description="Create Visitor",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={"id": 10, "name": "Jessica Smith"}
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={"name": "Jessica Smith"}
 *         )
 *     )
 * )
 */
