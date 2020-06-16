<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use Psr\Http\Message\ResponseInterface as Response;


class SexOffenderMatchAction extends SexOffenderAction
{
    protected function action(): Response
    {
        return $this->response->withStatus(201);
    }

     /**
      * @OA\Post(
      *     path="/people/{personId}/sex-offender/match",
      *     tags={"people", "sex-offenders"},
      *     @OA\Response(
      *         response=201,
      *         description="Create a match between a person and a sex offender",
      *         @OA\MediaType(
      *             mediaType="application/json"
      *         )
      *     ),
      *     @OA\RequestBody()
      * )
      */
}
