<?php
declare(strict_types=1);

namespace App\Actions\User;

define("AUTH_URL", 'https://test-auth.navigatep.com/');

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Exceptions;
use Psr\Log\LoggerInterface;
use App\Classes\TokenProcessor;
use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ForgotPasswordAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param TokenProcessor $tokenProcessor
     */

    public function __construct(LoggerInterface $logger, TokenProcessor $tokenProcessor)
    {
        $this->tokenProcessor = $tokenProcessor;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $formData = $this->getFormData();
        $username= $formData->username;

        $client = new GuzzleClient(['base_uri' => AUTH_URL, 'verify' => APP_ROOT . '/cacert.pem']);
 
        $data = ['app' => 'np', 'login' =>$username];
        
        try{
            $response = $client->post('api/password/emails', [
                'json' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());
            
            if ($response->getStatusCode() == 200) {
                return $this->respondWithData('success');
            }
            else {
                throw new Exceptions\InternalServerErrorException();
            }
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\BadRequestException($response->error->userMessage);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\InternalServerErrorException($response->error->userMessage);
        }    

        return $this->respondWithData([]);        
    }
}
