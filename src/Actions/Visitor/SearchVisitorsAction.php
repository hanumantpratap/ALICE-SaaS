<?php
declare(strict_types=1);

namespace App\Actions\Visitor;

use Psr\Http\Message\ResponseInterface as Response;

class SearchVisitorsAction extends VisitorAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $name = (string) $this->resolveArg('name');
        $visitors = $this->visitorRepository->findVisitorsOfName($name);

        $this->logger->info("Search on ${name} completed.");

        return $this->respondWithData($visitors);
    }
}
