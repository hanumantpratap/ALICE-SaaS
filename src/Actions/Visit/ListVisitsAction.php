<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ListVisitsAction extends Action
{
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
