<?php
declare(strict_types=1);

namespace App\Actions\Person;

use App\Domain\Person\BlacklistItem;
use Psr\Http\Message\ResponseInterface as Response;

class AddBlacklistAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $data = $this->getFormData();
    $personId = (int) $this->resolveArg("id");

    $blItem = new BlacklistItem();
    $blItem->personId = $personId;
    $blItem->notes = $data->notes;
    $blItem->userId = (int) $this->token->id;
    $blItem->buildingId = (int) $this->token->building;
    $blItem->reason = "";

    $person = $this->personRepository->findPersonOfId($blItem->personId);
    $blItem->setPerson($person);
    $person->addBlacklistItem($blItem);

    $this->personRepository->save($person);

    $this->logger->info("Blacklist Item Saved.");

    return $this->response->withStatus(201);
  }
}
 /**
 * @OA\POST(
 *     path="/persons/{personId}/blacklist",
 *     tags={"persons"},
 *      @OA\Parameter(
 *         name="personId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Blacklisted.",
 *     ),
 *     @OA\RequestBody(
 *         description="Add into BlackList",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(
 *                     property="persontId",
 *                     description="person ID (required)",
 *                     type="integer"
 *                 ),
 *              )
 *         ),
 *     )
 * )
 */