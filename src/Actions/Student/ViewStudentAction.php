<?php
declare(strict_types=1);

namespace App\Actions\Student;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

class ViewStudentAction extends StudentAction
{
    /**
     * {@inheritdoc}
     * OA\Get
     */
    protected function action(): Response
    {
        $studentId = (int) $this->resolveArg('id');
        $student = $this->studentRepository->findStudentOfId($studentId);
        $student->getParentAssociations();  // called so that the associations will be fetched and returned in json request

        $this->logger->info("Student of id `${studentId}` was viewed.");

        return $this->respondWithData($student);
    }
}
