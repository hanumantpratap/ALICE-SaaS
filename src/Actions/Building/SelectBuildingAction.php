<?php
declare(strict_types=1);

namespace App\Actions\Building;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Building\BuildingRepository;
use App\Classes\TokenProcessor;
use Psr\Http\Message\ResponseInterface as Response;

class SelectBuildingAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param BuildingRepository $buildingRepository
     * @param TokenProcessor $tokenProcessor
     */

    public function __construct(LoggerInterface $logger, BuildingRepository $buildingRepository, TokenProcessor $tokenProcessor)
    {
        $this->buildingRepository = $buildingRepository;
        $this->tokenProcessor = $tokenProcessor;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $buildingId = (int) $this->resolveArg('id');
        $building = $this->buildingRepository->findBuildingOfId($buildingId); // make sure it's a valid building
        
        $token = $this->token;
        $token->building = $buildingId;
        unset($token->encoded);
        $new_token = $this->tokenProcessor->create($token, 60*10, true);

        return $this->respondWithData(['token' => $new_token, 'tokenDecoded' => $token]);
    }
}

/**
 * @OA\Post(
 *     path="/buildings/{buildingsId}/tokens",
 *     tags={"buildings"},
 *     @OA\Parameter(
 *         name="Buildings",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Return Auth token for the Selected Building",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             example={"statusCode": 200, 
 *                      "data": {
 *                        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOisdfJIUzI1NiJ9.eyJ0eXBlIjoiYXV0aCIsImdpZCI6IjQ0MzExIiwiZGlzdCI6MzAwMCwiYWRtaW4iOiJmIiwiaWQiOjIwMDAwMDAwMSwiYnVpbGRpbmciOjMwMDEsInJlZGV4cCI6NjAwLCJpYXQiOjE1OTMxMTg0NDEsImV4cCI6bnVsbCwiZW5jb2RlZCI6ImV5SjBlWEFpT2lKS1YxUWlMQ0poYkdjaU9pSklVekkxTmlKOS5leUowZVhCbElqb2lZWFYwYUNJc0ltZHBaQ0k2SWpRME16RXhJaXdpWkdsemRDSTZNekF3TUN3aVlXUnRhVzRpT2lKbUlpd2lhV1FpT2pJd01EQXdNREF3TVN3aVluVnBiR1JwYm1jaU9qTXdNRElzSW5KbFpHVjRjQ0k2TmpBd0xDSnBZWFFpT2pFMU9USalV5T0RFc0ltVjRjQ0k2Ym5Wc2JIMC5fRXlVMlRHMTBGT2t6b0xIcXQyYmhYSWxGZDU3eTlUSDQ0V2NWQW5rT3lzIn0.vkKCMJl44AZaSrOuyg8AYI3b-RdzTm6jNsCOeioGZQs",
 *                        "tokenDecoded": {
 *                            "type": "auth",
 *                            "gid": "44311",
 *                            "dist": 3000,
 *                            "admin": "f",
 *                            "id": 200000001,
 *                            "building": 3001,
 *                            "redexp": 600,
 *                            "iat": 1593118441,
 *                            "exp": null,
 *                            "encoded": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NsdfiJ9.eyJ0eXBlIjoiYXV0aCIsImdpZCI6MzExIiwiZGlzdCI6MzAwMCwiYWRtaW4iOiJmIiwiaWQiOjIwMDAwMDAwMSwiYnVpbGRpbmciOjMwMDIsInJlZGV4cCI6NjAwLCJpYXQiOjE1OTI4NjUyODEsImV4cCI6bnVsbH0._EyU2TG10FOkzoLHqt2bhXIlFd57y9TH44WcVAnkOys"
 *                        }
 *                     }}
 *         )
 *     )
 * )
 */
