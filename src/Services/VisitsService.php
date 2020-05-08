<?php
namespace App\Services;

use App\Classes\DistrictDatabaseConnection;
use Psr\Log\LoggerInterface;
use App\Exceptions;

class VisitsService
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

    public function fetchAll() {
        $visits = array();

        $sql = "SELECT
                    Visits.id,
                    Visits.people_id as \"personId\",
                    PersonNames.given_name || ' ' || PersonNames.family_name as \"personName\",
                    Visits.date_created as \"dateCreated\",
                    Visits.check_in as \"checkIn\",
                    Visits.check_out as \"checkOut\",
                    Visits.user_id as \"userId\",
                    UserNames.given_name || ' ' || PersonNames.family_name as \"userName\",
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

        return $visits;
    }

    public function fetch($visitId) {
        $sql = "SELECT
                    Visits.id,
                    Visits.people_id as \"personId\",
                    PersonNames.given_name || ' ' || PersonNames.family_name as \"personName\",
                    Visits.date_created as \"dateCreated\",
                    Visits.check_in as \"checkIn\",
                    Visits.check_out as \"checkOut\",
                    Visits.user_id as \"userId\",
                    UserNames.given_name || ' ' || PersonNames.family_name as \"userName\",
                    Visits.notes
                FROM 
                    visitor_management.visits As Visits
                    LEFT JOIN public.person_names AS PersonNames
                        ON Visits.people_id = PersonNames.person_id
                    LEFT JOIN public.person_account AS Users
                        ON Visits.user_id = Users.pa_id
                    LEFT JOIN public.person_names AS UserNames
                        ON Users.person_id = UserNames.person_id
                WHERE
                    Visits.id = :visitId";

        $query = $this->districtDB->run($sql, ['visitId' => $visitId]);

        if ($query->rowCount() > 0) {
            $visit = $query->fetch();
        }
        else {
            throw new Exceptions\NotFoundException("Could not find Visit of id `${visitId}`");
        }

        return $visit;
    }

    public function add($visitData) {
        $sql = "INSERT INTO visitor_management.visits
                (people_id, date_created, user_id, notes)
                VALUES(:visitorId, now(), :userId, :notes)
                RETURNING id";

        $query = $this->districtDB->run($sql, [
            'visitorId' => $visitData->visitorId, 
            'userId' => $visitData->userId,
            'notes' => $visitData->notes
        ]);

        if ($query->rowCount() > 0) {
            $visitId = $query->fetch()['id'];
        }
        else {
            throw new Exceptions\InternalServerErrorException("Could not create Visit Record.");
        }

        return $visitId;
    }
}
