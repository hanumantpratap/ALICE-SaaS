<?php
declare(strict_types=1);

namespace App\Actions\Visitor;

use Psr\Http\Message\ResponseInterface as Response;

class ViewVisitorAction extends VisitorAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $visitors = $this->visitorRepository->findAll();

        $this->logger->info("All visitors retrieved.");

        return $this->respondWithData($visitors);
    }
}
