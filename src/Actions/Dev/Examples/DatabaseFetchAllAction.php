<?php
declare(strict_types=1);

namespace App\Actions\Dev\Examples;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\DatabaseConnection;

class DatabaseFetchAllAction extends Action
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
                    prepared.teams";

        $query = $this->database->run($sql);

        $rowCount = $query->rowCount();

        if ($query->rowCount() > 0) {
            $teams = $query->fetchAll();
        }
        else {
            throw \App\Exceptions\NotFoundException("Could not find any teams.");
        }

        return $this->respondWithData(['teams' => $teams]);
    }
}
