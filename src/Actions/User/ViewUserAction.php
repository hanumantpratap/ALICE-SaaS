<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ViewUserAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $this->logger->info("User of id `${userId}` was viewed.");

        return $this->respondWithData(['params' => 'value']);
    }
}
