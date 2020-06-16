<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Infrastructure\Persistence\User;
use Slim\Exception\HttpBadRequestException;

use Psr\Http\Message\ResponseInterface as Response;

class ApproveVisitAction extends VisitAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        
        $this->logger->info("Id " . $this->resolveArg('id'));

        $visitId = (int) $this->resolveArg('id');
        $visit = $this->visitRepository->findVisitOfId($visitId);

        $this->logger->info("Approving visit of id `${visitId}`");

        if (!isset($formData->approvedBy)) throw new HttpBadRequestException( $this->request, "Missing parameter, approvedBy" );
        $approvedBy = $formData->approvedBy;

        $user = $this->userRepository->findUserOfId( $approvedBy);
        $visit->setApprovedByUser( $user );
        $visit->approved = true;
        $this->visitRepository->save($visit);

        return $this->respondWithData();
    }
}