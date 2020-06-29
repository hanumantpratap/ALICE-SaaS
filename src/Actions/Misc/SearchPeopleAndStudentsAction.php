<?php
declare(strict_types=1);

namespace App\Actions\Misc;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SearchPeopleAndStudentsAction extends Action
{
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        parent::__construct($logger);
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();

        if (isset($params['name'])) {
            $name = '%' . $params['name'] . '%';
        }

        $sql = "
            SELECT
                id,
                first_name as \"firstName\",
                last_name as \"lastName\",
                middle_initial as \"middleName\",
                dob,
                grade,
                'student' as type
            FROM
                respond.students
            WHERE
                inactive = false
        ";

        if ($name) {
            $sql .= "AND (
                 (first_name ILIKE'$name')
                 OR (last_name ILIKE '$name') )";
        }

        $sql .= "
            UNION

            SELECT
                Person.person_id as id,
                Name.given_name as \"firstName\",
                Name.family_name as \"lastName\",
                Name.middle_name as \"middleName\",
                Demographics.birth_date as dob,
                null as grade,
                'person' as type
            FROM
                public.people AS Person
            LEFT JOIN 
                public.person_names AS Name
                    ON Person.person_id = Name.person_id
            LEFT JOIN
                public.person_demographics AS Demographics
                    ON Person.person_id = Demographics.person_id
        ";

        if ($name) {
            $sql .= "WHERE
                 (Name.given_name ILIKE'$name')
                 OR (Name.family_name ILIKE '$name')";
        }

        $sql .= "ORDER BY \"firstName\" ASC;";

        $query = $this->entityManager->getConnection()->query($sql);
        $results = $query->fetchAll();

        return $this->respondWithData([$results]);
    }
    /**
     * @OA\Get(
     *     path="/people-students/search/query",
     *     tags={"misc"},
     *      @OA\Response(
     *         response=200,
     *         description="Combined People and Students search",
     *     )
     * )
     */
}