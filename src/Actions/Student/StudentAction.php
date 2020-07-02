<?php
declare(strict_types=1);

namespace App\Actions\Student;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Student\StudentRepository;
use App\Domain\Person\PersonRepository;


abstract class StudentAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param StudentRepository $studentRepository
     * @param ParentRepository $parentRepository
     */

    public function __construct(LoggerInterface $logger, StudentRepository $studentRepository, PersonRepository $personRepository )
    {
        parent::__construct($logger);
        $this->studentRepository = $studentRepository;
        $this->personRepository = $personRepository;
    }
}
