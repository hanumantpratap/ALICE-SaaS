<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateVisitReasonAction extends VisitReasonAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $visitReasonId = (int) $this->resolveArg('id');
        $visitReason = $this->visitReasonRepository->findVisitReasonOfId($visitReasonId);

        $this->logger->info("Updating Visit Reason of id `${visitReasonId}`");

        if (isset($formData->type)) {
            $visitReason->setType($formData->type);
        }

        if (isset($formData->inactive)) {
            $visitReason->setInactive($formData->inactive ? true : false );
        }

        $this->visitReasonRepository->save($visitReason);

        return $this->respondWithData();
    }
}