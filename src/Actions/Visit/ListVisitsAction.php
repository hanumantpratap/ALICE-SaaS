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
                    *
                FROM 
                    visitor_management.visits";

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
     *         description="View Visitor",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"id": 10, "visitor_name": "Jessica Smith"}
     *         )
     *     )
     * )
     */
}
