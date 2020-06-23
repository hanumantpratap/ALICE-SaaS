<?php
namespace App\Classes;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use Psr\Log\LoggerInterface;
use App\Exceptions;

class FamilyWatchDog {
    private $apiUrl = 'http://services.familywatchdog.us/rest/json.asp';
    private $key = '32DEBCD7-5B3F-4A7B-B41F-9F6EFDA79296';
    private $guzzle;

    function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
        $this->guzzle = new GuzzleClient(['base_uri' => $this->apiUrl]);
    }

    public function search(string $firstName, string $lastName, string $birthDate = null) {
        $data = [
            'key' => $this->key,
            'type' => 'searchbynamedob',
            'fname' => $firstName,
            'lname' => $lastName
        ];

        if ($birthDate !== null) {
            $data['dob'] = $birthDate;
        }

        $this->logger->info('FWD sex offender search', $data);

        try {
            $response = $this->guzzle->request('GET', '', [
                'query' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());

            if ($payload->status != 'ok') {
                throw new Exceptions\BadRequestException('Connection to SOR failed.');
            }

            return $payload;
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\BadRequestException($response->summary);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }
        catch(ConnectException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }
    }

    public function get(string $offenderId) {
        $data = [
            'key' => $this->key,
            'type' => 'getoffender',
            'offenderid' => $offenderId
        ];

        $this->logger->info("FWD sex offender get by ID `${offenderId}`");

        try {
            $response = $this->guzzle->request('GET', '', [
                'query' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());

            if (!isset($payload->status) || $payload->status != 'ok') {
                throw new Exceptions\BadRequestException('Connection to SOR failed.');
            }

            return $payload;
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\BadRequestException($response->summary);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }
        catch(ConnectException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }
    }
}