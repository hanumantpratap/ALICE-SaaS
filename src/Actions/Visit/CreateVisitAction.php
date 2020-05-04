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
 *             example={"statusCode": 200, 
 *                      "data": {
 *                              "id": 10, 
 *                              "visitor_id": 12, 
 *                              "visitor_name": "Jessica Smith", 
 *                              "date_created": "2020-05-01 11:15:40",
 *                              "check_in": "2020-05-01 11:15:40",
 *                              "check_out": "2020-05-01 11:15:40",
 *                              "user_id": 3,
 *                              "user_name": "Mike Jones",
 *                              "notes": "Here are some notes."
 *                          }}
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
