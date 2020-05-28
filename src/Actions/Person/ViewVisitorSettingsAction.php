<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

class ViewVisitorSettingsAction extends PersonAction
{
    /**
     * {@inheritdoc}
     * OA\Get
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($userId);
        $settings = $person->getVisitorSettings();

        if ($settings === null) {
            $this->logger->info("no settings found");
        }
        
        testLog('settings', $settings);

        $this->logger->info("Visitor Settings for Person of id `${userId}` was viewed.");

        return $this->respondWithData($settings);
    }
}
