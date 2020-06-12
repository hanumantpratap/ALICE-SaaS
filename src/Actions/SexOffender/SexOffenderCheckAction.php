<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use Throwable;
use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectionException;
use App\Exceptions;

class SexOffenderCheckAction extends SexOffenderAction
{
    protected function action(): Response
    {
        /* $formData = $this->getFormData();

        $firstName = trim($formData->firstName ?: '');
        $lastName = trim($formData->lastName ?: '');
        $dob = null;
        if (strlen($formData->dob ?: '')) {
            try {
                $date = new DateTime($formData->dob);
                $dob = $date->format('m/d/Y');
            } catch (Throwable $e) {}
        } */

        $personId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($personId);
        $name = $person->getName();

        $data = [
            'key' => '32DEBCD7-5B3F-4A7B-B41F-9F6EFDA79296',
            'type' => 'searchbynamedob',
            'fname' => $name->getGivenName(),
            'lname' => $name->getFamilyName()
        ];

        $demographics = $person->getDemographics();
        $birthDate = $demographics ? $demographics->getBirthDate() : null;

        if ($birthDate !== null) {
            $data['dob'] = $birthDate;
        }

        $this->logger->info('FWD sex offender search', $data);

        $client = new Client(['base_uri' => 'http://services.familywatchdog.us/rest/json.asp']);

        try {
            $response = $client->request('GET', '', [
                'query' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());

            if ($payload->status != 'ok') {
                throw new Exceptions\BadRequestException('Connection to SOR failed.');
            }
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\BadRequestException($response->summary);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }
        catch(ConnectionException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }

        /* if (count($payload->offenders)) {
            $sql = "
                INSERT INTO
                    visitor_management.sex_offenders
                    (offender_id, data)
                VALUES
                    (:offender_id, :data)
                ON CONFLICT
                    (offender_id)
                DO UPDATE
                SET data = :data;
            ";

            foreach ($payload->offenders as $offender) {
                $stmt = $this->entityManager->getConnection()->prepare($sql);
                $stmt->execute([
                    'offender_id' => $offender->offenderid,
                    'data' => json_encode($offender)
                ]);
            }
        } */

        return $this->respondWithData($payload->offenders);
    }

     /**
      * @OA\Post(
      *     path="/sex-offender-check",
      *     tags={"sex-offenders"},
      *     @OA\Response(
      *         response=200,
      *         description="Check if a person is a registered sex offender",
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             example={"id": 10, "name": "Jessica Smith"}
      *         )
      *     )
      * )
      */
}
