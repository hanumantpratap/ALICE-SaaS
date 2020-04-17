<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ViewVisitAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $visitId = (int) $this->resolveArg('id');

        $this->logger->info("Visit of id `${visitId}` was viewed.");

        return $this->respondWithData([]);
    }
}
