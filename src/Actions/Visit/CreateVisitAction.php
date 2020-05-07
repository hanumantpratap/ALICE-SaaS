<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;

class CreateVisitAction extends VisitAction
{
    protected function action(): Response
    {
        $visitData = $this->getFormData();
        $bad_fields = array();

        if (!isset($visitData->visitorId)) {
            array_push($bad_fields, ['field' => 'visitorId', 'message' => 'You must provide a visitorId.']);
        }

        if (count($bad_fields) > 0) {
            throw new Exceptions\BadRequestException(null, $bad_fields);
        }

        $visitData->userId = $this->token->id;

        $visitId = $this->visitsService->add($visitData);
        $visit = $this->visitsService->fetch($visitId);

        return $this->respondWithData($visit);
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
 *                            "id": 2,
 *                            "personId": 1,
 *                            "personName": "Lauren Admin",
 *                            "dateCreated": "2020-05-01 15:15:40.638842+00",
 *                            "checkIn": null,
 *                            "checkOut": null,
 *                            "userId": 200000037,
 *                            "userName": "Mae Admin",
 *                            "notes": "test"
 *                       }}
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={
 *                  "visitorId": 3185,
 *                  "notes": "hello"
 *            }
 *         )
 *     )
 * )
 */
