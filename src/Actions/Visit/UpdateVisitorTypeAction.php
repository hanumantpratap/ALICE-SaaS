<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateVisitorTypeAction extends VisitorTypeAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $visitorTypeId = (int) $this->resolveArg('id');
        $visitorType = $this->visitorTypeRepository->findVisitorTypeOfId($visitorTypeId);

        $this->logger->info("Updating Visitor Type of id `${visitorTypeId}`");

        if (isset($formData->type)) {
            $visitorType->setType($formData->type);
        }

        if (isset($formData->inactive)) {
            $visitorType->setInactive($formData->inactive ? true : false );
        }

        $this->visitorTypeRepository->save($visitorType);

        return $this->respondWithData();
    }
}