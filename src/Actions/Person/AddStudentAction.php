<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Person\PersonRepository;
use App\Domain\Student\StudentRepository;
use Psr\Log\LoggerInterface;
use App\Exceptions;

class AddStudentAction extends PersonAction
{
  public function __construct(LoggerInterface $logger, PersonRepository $personRepository, StudentRepository $studentRepository)
    {
        parent::__construct($logger, $personRepository);
        $this->studentRepository = $studentRepository;
    }

  protected function action(): Response {
    $data = $this->getFormData();
    $personId = (int) $this->resolveArg("id");
    $person = $this->personRepository->findPersonOfId($personId);

    if (!isset($data->studentId)) {
      throw new Exceptions\BadRequestException('Please provide a student ID.');
    }

    $student = $this->studentRepository->findStudentOfId((int) $data->studentId);
    $person->addStudent($student, 1);
    $this->personRepository->save($person);

    $studentId = $student->getId();
    $this->logger->info("Student of id `${studentId}` added to Person of id `${personId}`");

    return $this->response->withStatus(201);
  }
}
