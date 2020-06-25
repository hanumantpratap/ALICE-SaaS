<?php
declare(strict_types=1);

namespace App\Actions\Student;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateStudentAction extends StudentAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $studentId = (int) $this->resolveArg('id');
        $student = $this->studentRepository->findStudentOfId($studentId);

        $this->logger->info("Updating Student of id `${studentId}`");

        if (isset($formData->firstName)) {
            $student->firstName = $formData->firstName;
        }

        if (isset($formData->lastName)) {
            $student->lastName = $formData->lastName;
        }

        if (isset($formData->middleInitial)) {
            $student->middleInitial = $formData->middleInitial;
        }        

        if (isset($formData->suffix)) {
            $student->suffix = $formData->suffix;
        }

        if (isset($formData->gender)) {
            $student->gender = $formData->gender;
        }

        if (isset($formData->dob)) {
            $student->dob = $formData->dob;
        }

        if (isset($formData->grade)) {
            $student->grade = $formData->grade;
        }

        if (isset($formData->inactive)) {
            $student->inactive = $formData->inactive;
        }

        $this->studentRepository->save($student);

        return $this->respondWithData();
    }
}
/**
 * @OA\Put(
 *     path="/students/{studentId}",
 *     tags={"students"},
 *     @OA\Response(
 *         response=200,
 *         description="student updated",
 *     )
 * )
 */