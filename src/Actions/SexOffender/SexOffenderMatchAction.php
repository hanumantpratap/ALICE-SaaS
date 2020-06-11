<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use Throwable;
use DateTime;
use App\Domain\SexOffender\SexOffender;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\SexOffender\SexOffenderRepository;
use App\Actions\Action;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectionException;
use App\Exceptions;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class SexOffenderMatchAction extends Action
{
    public function __construct(LoggerInterface $logger, SexOffenderRepository $sexOffenderRepository, EntityManagerInterface $entityManager)
    {
        $this->sexOffenderRepository = $sexOffenderRepository;
        $this->entityManager = $entityManager;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        return $this->response->withStatus(201);
    }

     /**
      * @OA\Post(
      *     path="/people/{personId}/sex-offender/match",
      *     tags={"people", "sex-offenders"},
      *     @OA\Response(
      *         response=201,
      *         description="Create a match between a person and a sex offender",
      *         @OA\MediaType(
      *             mediaType="application/json"
      *         )
      *     ),
      *     @OA\RequestBody()
      * )
      */
}
