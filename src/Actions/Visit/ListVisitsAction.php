<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\DistrictDatabaseConnection;

class ListVisitsAction extends Action
{
    /**
     * @param DistrictDatabaseConnection $districtDB
     * @param LoggerInterface $logger
     */

    public function __construct(DistrictDatabaseConnection $districtDB, LoggerInterface $logger)
    {
        $this->districtDB = $districtDB;
        $this->logger = $logger;
    }

    protected function action(): Response
    {
        $visits = array();
        
        $sql = "SELECT
                    Visits.id,
                    Visits.people_id as visitor_id,
                    PersonNames.given_name || ' ' || PersonNames.family_name as visitor_name,
                    Visits.date_created,
                    Visits.check_in,
                    Visits.check_out,
                    Visits.user_id,
                    UserNames.given_name || ' ' || PersonNames.family_name as user_name,
                    Visits.notes
                FROM 
                    visitor_management.visits As Visits
                    LEFT JOIN public.person_names AS PersonNames
                        ON Visits.people_id = PersonNames.person_id
                    LEFT JOIN public.person_account AS Users
                        ON Visits.user_id = Users.pa_id
                    LEFT JOIN public.person_names AS UserNames
                        ON Users.person_id = UserNames.person_id";

        $query = $this->districtDB->run($sql);

        if ($query->rowCount() > 0) {
            $visits = $query->fetchAll();
        }

        $this->logger->info("Visits list was viewed.");

        return $this->respondWithData($visits);
    }

    /**
     * @OA\Get(
     *     path="/visits",
     *     tags={"visits"},
     *     @OA\Parameter(
     *         name="visitor_name",
     *         in="query",
     *         description="Filter by visitor",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="View Visits",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          {
     *                              "id": 10, 
    *                               "visitor_id": 12, 
     *                              "visitor_name": "Jessica Smith", 
     *                              "date_created": "2020-05-01 11:15:40",
     *                              "check_in": "2020-05-01 11:15:40",
     *                              "check_out": "2020-05-01 11:15:40",
     *                              "user_id": 3,
     *                              "user_name": "Mike Jones",
     *                              "notes": "Here are some notes."
     *                          },
     *                          {
     *                              "id": 11, 
    *                               "visitor_id": 13, 
     *                              "visitor_name": "Dan Brown", 
     *                              "date_created": "2020-05-01 11:15:40",
     *                              "check_in": "2020-05-01 11:15:40",
     *                              "check_out": "2020-05-01 11:15:40",
     *                              "user_id": 4,
     *                              "user_name": "Amy Davis",
     *                              "notes": "Here are some notes."
     *                          }
     *                      }}
     *         )
     *     )
     * )
     */
}
