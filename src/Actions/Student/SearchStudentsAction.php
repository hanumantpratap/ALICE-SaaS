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

     /**
     * @OA\Get(
     *     path="/students/search/query",
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
     *                         },
     *                         {
     *                              "id": 67,
     *                              "firstName": "Arianna",
     *                              "lastName": "Jones",
     *                              "middleInitial": "Gr",
     *                              "suffix": null,
     *                              "studentNumber": "44e24bc8-6723-44ds2-be4a-9ec200d022fe",
     *                              "gender": "F",
     *                              "dob": {
     *                                  "date": "2005-11-23 00:00:00.000000",
     *                                  "timezone_type": 3,
     *                                  "timezone": "UTC"
     *                              },
     *                              "grade": "04",
     *                              "inactive": false
     *                          }
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}
