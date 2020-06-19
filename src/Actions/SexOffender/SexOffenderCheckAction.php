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

        if (count($payload->offenders) > 2) {
            $payload->offenders = [$payload->offenders[1]];
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
      *     path="/persons/{personId}/sex-offender-check",
      *     tags={"sex-offenders"},
      *     @OA\Response(
      *         response=200,
      *         description="Check if a person is a registered sex offender",
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             example={"statusCode": 200,
      *                      "data": {
      *                          {
      *                             "offenderid": "ILE15A5858",
      *                             "photo": "http://photo.familywatchdog.us/OffenderPhoto/OffenderPhoto.aspx?id=ILE15A5858",
      *                             "name": "JEFFREY A HUFFMAN",
      *                             "firstname": "JEFFREY",
      *                             "middlename": "A",
      *                             "lastname": "HUFFMAN",
      *                             "suffix": "",
      *                             "dob": "3/22/1963",
      *                             "age": "57",
      *                             "sex": "M",
      *                             "race": "W",
      *                             "hair": "",
      *                             "eye": "",
      *                             "height": "6ft 01in",
      *                             "weight": "210",
      *                             "street1": "1714 S WEST LN",
      *                             "street2": "",
      *                             "city": "PEORIA",
      *                             "state": "IL",
      *                             "zipcode": "61605",
      *                             "county": "",
      *                             "matchtype": "0",
      *                             "latitude": "40.66856",
      *                             "longitude": "-89.64806",
      *                             "convictiontype": ""
      *                        },
     *                      }
     *                  }
      *         )
      *     )
      * )
      */
}
