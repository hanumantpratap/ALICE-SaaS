<?php
declare(strict_types=1);

namespace App\Actions\Student;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;


class AddParentToStudentAction extends StudentAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $formData = $this->getFormData();
    $studentId = (int) $this->resolveArg("id");
    $student = $this->studentRepository->findStudentOfId($studentId);

    if (!isset($formData->personId)) {
      throw new Exceptions\BadRequestException('Missing parameter, personId.');
    }

    if (!isset($formData->associationTypeId)) {
        throw new Exceptions\BadRequestException('Missing parameter, associationTypeId.');
    }

    $person = $this->personRepository->findPersonOfId((int) $formData->personId);
    
    $student->addParentAssociation($person, (int)$formData->associationTypeId );
    $this->studentRepository->save($student);

    $this->logger->info("Added Parent to Student");

    return $this->response->withStatus(201);
  }
}
