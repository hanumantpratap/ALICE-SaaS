<?php
declare(strict_types=1);

namespace App\Actions\Student;

use Psr\Http\Message\ResponseInterface as Response;

class SearchStudentsAction extends StudentAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();
        $students = $this->studentRepository->findStudentsByParams($params);
 
        $this->logger->info("SearchStudentsAction::action() retrieved the records." );

        return $this->respondWithData($students);
    }
}
