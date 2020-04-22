<?php
declare(strict_types=1);

namespace App\Actions\Dev\Examples;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\DatabaseConnection;

class DatabaseFetchAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param DatabaseConnection $coreDb
     */

    public function __construct(DatabaseConnection $database, LoggerInterface $logger)
    {
        $this->database = $database;
        $this->logger = $logger;
    }
       
    protected function action(): Response
    {
        $sql = "SELECT
                    *
                FROM 
                    prepared.teams
                WHERE
                    team_id = :team_id";

        $query = $this->database->run($sql, ['team_id' => 5235]);

        $rowCount = $query->rowCount();
        $this->logger->info('Row count: ' . $rowCount);

        if ($query->rowCount() > 0) {
            $team = $query->fetch();
        }
        else {
            throw \App\Exceptions\NotFoundException("Could not find the selected team.");
        }

        return $this->respondWithData(['team' => $team]);
    }
}
