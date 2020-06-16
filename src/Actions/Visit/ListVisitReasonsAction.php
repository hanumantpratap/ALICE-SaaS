<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ListVisitReasonsAction extends VisitReasonAction
{
    protected function action(): Response
    {
        $visitReasons = $this->visitReasonRepository->findAll();

        $this->logger->info("Visit Reason list was viewed.");
        
        return $this->respondWithData($visitReasons);
    }
}