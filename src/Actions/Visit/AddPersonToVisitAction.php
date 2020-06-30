<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Domain\Visit\VisitHasPeople;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;


class AddPersonToVisitAction extends VisitAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
     $formData = $this->getFormData();
    $visitId = (int) $this->resolveArg("id");
    $visit = $this->visitRepository->findVisitOfId($visitId);

    if (!isset($formData->personId)) {
      throw new Exceptions\BadRequestException('Missing parameter, personId.');
    }

    $person = $this->personRepository->findPersonOfId((int) $formData->personId);
    $visit->addPersonToVisit($person);
    $this->visitRepository->save($visit);

    $this->logger->info("Added VisitHasPerson record");

    return $this->response->withStatus(201);    
  }
}
