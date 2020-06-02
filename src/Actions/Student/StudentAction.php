<?php
declare(strict_types=1);

namespace App\Actions\Student;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Student\StudentRepository;

abstract class StudentAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param StudentRepository $studentRepository
     */

    public function __construct(LoggerInterface $logger, StudentRepository $studentRepository)
    {
        parent::__construct($logger);
        $this->studentRepository = $studentRepository;
    }
}
