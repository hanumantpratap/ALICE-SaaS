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

     /**
     * @OA\Get(
     *     path="/students/{studentId}",
     *     tags={"students"},
     *      @OA\Response(
     *         response=200,
     *         description="View Student",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          {
     *                              "id": 15,
     *                              "firstName": "Jamie",
     *                              "lastName": "Clinton",
     *                              "middleInitial": "Sk",
     *                              "suffix": null,
     *                              "studentNumber": "70e19784-cs196-4e30-99fa-sd",
     *                              "gender": "F",
     *                              "dob": {
     *                                  "date": "2005-05-06 00:00:00.000000",
     *                                  "timezone_type": 3,
     *                                  "timezone": "UTC"
     *                              },
     *                              "grade": "05",
     *                              "inactive": false
     *                         }
     *                         
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}
