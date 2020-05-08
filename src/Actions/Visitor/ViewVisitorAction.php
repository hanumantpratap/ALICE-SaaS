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
        $userId = (int) $this->resolveArg('id');
        $visitor = $this->visitorRepository->findVisitorOfId($userId);

        $this->logger->info("Visitor of id `${userId}` was viewed.");

        return $this->respondWithData($visitor);
    }
}
