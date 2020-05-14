<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ViewVisitAction extends VisitAction
{
    protected function action(): Response
    {
        $visitId = (int) $this->resolveArg('id');
        $visit = $this->repository->findOneBy(['id' => $visitId]);
        $person = $visit->getPerson();
        //$visit = $this->visitsService->fetch($visitId);

        $this->logger->info("Visit of id `${visitId}` was viewed.");

        return $this->respondWithData(['visit' => $visit, 'person' => $person]);
    }

    /**
     * @OA\Get(
     *     path="/visits/{visitId}",
     *     tags={"visits"},
     *     @OA\Parameter(
     *         name="visitId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="View Visitor",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200, "data": {
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
     *     )
     * )
     */
}
