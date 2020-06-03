<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Person\PersonRepository;
use App\Domain\Student\StudentRepository;
use Psr\Log\LoggerInterface;
use App\Exceptions;

class RemoveStudentAction extends PersonAction
{
  public function __construct(LoggerInterface $logger, PersonRepository $personRepository, StudentRepository $studentRepository)
    {
        parent::__construct($logger, $personRepository);
        $this->studentRepository = $studentRepository;
    }

  protected function action(): Response {
    $personId = (int) $this->resolveArg("id");
    $person = $this->personRepository->findPersonOfId($personId);
    $studentId = (int) $this->resolveArg("studentId");
    $student = $this->studentRepository->findStudentOfId($studentId);
    
    $person->removeStudent($student);
    $this->personRepository->save($person);

    return $this->response->withStatus(201);
  }
}
