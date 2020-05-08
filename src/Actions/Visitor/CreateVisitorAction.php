<?php
declare(strict_types=1);

namespace App\Actions\Visitor;

use App\Actions\ActionPayload;
use App\Domain\Visitor\Visitor;
use Psr\Http\Message\ResponseInterface as Response;

class CreateVisitorAction extends VisitorAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $visitor = $this->getFormData();
        $visitor = $this->visitorRepository->save($visitor);

        $this->logger->info("Visitor of name `${visitor->name}` was viewed.");

        $payload = new ActionPayload(201);

        return $this->respond($payload);
    }
}
