<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Domain\Visit\VisitorType;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;


class CreateVisitorTypeAction extends VisitorTypeAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $formData = $this->getFormData();
    $this->logger->info('post new visitor type', (array) $formData);

    if (!isset($formData->type))  {
        throw new Exceptions\BadRequestException('Missing parameter, type.');
    }

    $visitorType = new VisitorType();
    $visitorType->setType( $formData->type );

    $this->visitorTypeRepository->save( $visitorType );

    $this->logger->info("New Visit Type saved.");
    return $this->respondWithData(['message' => 'Visitor Type created', 'id' => $visitorType->id], 201);
  }
}
/**
 * @OA\Post(
 *     path="/visitor-types",
 *     tags={"visitor-type"},
 *     @OA\Response(
 *         response=200,
 *         description="New Visitor Type",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={"statusCode": 200, 
 *                      "data": {
 *                          
 *                       }}
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Create new Visitor Type",
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
