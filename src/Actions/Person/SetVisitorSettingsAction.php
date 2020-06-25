<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
use App\Domain\Person\VisitorSettings;

class SetVisitorSettingsAction extends PersonAction
{
    /**
     * {@inheritdoc}
     * OA\Get
     */
    protected function action(): Response
    {
        $personId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($personId);

        $data = $this->getFormData();

        $settings = $person->getVisitorSettings();

        if ($settings === null) {
            $settings = new VisitorSettings();
            $settings->setPerson($person);
            $person->setVisitorSettings($settings);
        }

        if (isset($data->picture)) {
            $settings->setPicture($data->picture);
        }

        $this->personRepository->save($person);
        $this->logger->info("Visitor Settings for Person of id `${personId}` were saved.");

        return $this->respondWithData($settings);
    }
}


/**
 * @OA\Put(
 *     path="/persons/{personId}/visitorSettings",
 *     tags={"persons"},
 *     @OA\Response(
 *         response=200,
 *         description="Update VisitorSettings",
 *     )
 * )
 */