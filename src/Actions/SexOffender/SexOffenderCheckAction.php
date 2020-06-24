<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use Psr\Http\Message\ResponseInterface as Response;

class SexOffenderCheckAction extends SexOffenderAction
{
    protected function action(): Response
    {
        $personId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($personId);

        $alreadyMatched = false;
        $sexOffenderMatch = $person->getSexOffenderMatch();

        if ($sexOffenderMatch !== null) {
            // visitor has already been matched to a sex offender - pull updated information
            $payload = $this->familyWatchDog->get($sexOffenderMatch->getSexOffenderId());
            $offenders[] = $payload->offender;
            $alreadyMatched = true;
        }
        else {
            // visitor has not been matched - perform regular search
            $name = $person->getName();
            $demographics = $person->getDemographics();
            $birthDate = $demographics ? $demographics->getBirthDate() : null;
            $birthDate = $birthDate ? $birthDate->format('m/d/Y') : null;

            $payload = $this->familyWatchDog->search($name->getGivenName(), $name->getFamilyName(), $birthDate);
            $offenders = $payload->offenders;
        }

        if (count($offenders)) {
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

            foreach ($offenders as $offender) {
                $stmt = $this->entityManager->getConnection()->prepare($sql);
                $stmt->execute([
                    'offender_id' => $offender->offenderid,
                    'data' => json_encode($offender)
                ]);
            }
        }
        else {
            return $this->respondWithData([]);
        }

        if (!$alreadyMatched) {
            // remove from list if this offender has already been marked as a non-match
            $offenders = $person->pruneOffendersList($offenders);
            return $this->respondWithData(['possibleMatches' => $offenders]);
        }
        else {
            return $this->respondWithData(['match' => $offenders[0]]);
        }
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
