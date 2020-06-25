<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

class ViewVisitorSettingsAction extends PersonAction
{
    /**
     * {@inheritdoc}
     * OA\Get
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($userId);
        $settings = $person->getVisitorSettings();

        if ($settings === null) {
            $this->logger->info("no settings found");
        }
        
        $this->logger->info("Visitor Settings for Person of id `${userId}` was viewed.");

        return $this->respondWithData($settings);
    }
}
 /**
     * @OA\Get(
     *     path="/persons/{personId}/visitorSettings",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="View Visitor Settings",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          {
     *                              "personId": 3461,
     *                              "status": 1,
     *                              "displayName": "McKellen, Sean",
     *                              "externalId": null,
     *                              "type": null,
     *                              "name": {
     *                                  "id": 3396,
     *                                  "personId": 3461,
     *                                  "nameType": 2,
     *                                  "givenName": "Sean",
     *                                  "middleName": null,
     *                                  "familyName": "McKellen",
     *                                  "nickName": null,
     *                                  "suffix": null,
     *                                  "title": null
     *                              },
     *                          },
     *                      }
     *                  }
     *         )
     *     )
     * )
     */