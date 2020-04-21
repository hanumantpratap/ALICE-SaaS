<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\DatabaseConnection;

class ListVisitsAction extends Action
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
    
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $visits = array();

        $this->logger->info("Visits list was viewed.");

        return $this->respondWithData($visits);
    }
}
