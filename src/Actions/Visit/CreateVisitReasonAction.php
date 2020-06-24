<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Domain\Visit\VisitReason;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;


class CreateVisitReasonAction extends VisitReasonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $formData = $this->getFormData();
    $this->logger->info('post new visit reason', (array) $formData);

    if (!isset($formData->type))  {
        throw new Exceptions\BadRequestException('Missing parameter, type.');
    }

    $visitReason = new VisitReason();
    $visitReason->setType( $formData->type );

    $this->visitReasonRepository->save( $visitReason );

    $this->logger->info("New Visit Reason saved.");
    return $this->respondWithData(['message' => 'Visit Reason created', 'id' => $visitReason->id], 201);
  }
}
