<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ListVisitorTypesAction extends VisitorTypeAction
{
    protected function action(): Response
    {
        $visitorTypes = $this->visitorTypeRepository->findAll();

        $this->logger->info("Visitor Type list was viewed.");
        
        return $this->respondWithData($visitorTypes);
    }
}