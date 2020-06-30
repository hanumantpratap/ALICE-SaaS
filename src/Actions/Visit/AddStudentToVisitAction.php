<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Domain\Visit\VisitHasStudents;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;


class AddStudentToVisitAction extends VisitAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $formData = $this->getFormData();
    $visitId = (int) $this->resolveArg("id");
    $visit = $this->visitRepository->findVisitOfId($visitId);

    if (!isset($formData->studentId)) {
      throw new Exceptions\BadRequestException('Missing parameter, studentId.');
    }

    $student = $this->studentRepository->findStudentOfId((int) $formData->studentId);
    $visit->addStudentToVisit($student);
    $this->studentRepository->save($student);

    $this->logger->info("Added VisitHasStudent record");

    return $this->response->withStatus(201);    
  }
}
