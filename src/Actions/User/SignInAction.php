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
use Psr\Container\ContainerInterface;
use App\Domain\User\UserRepository;

class SignInAction extends Action
{
    /**
     * @param ContainerInterface $c
     * @param LoggerInterface $logger
     * @param TokenProcessor $tokenProcessor
     */

    public function __construct(ContainerInterface $container, LoggerInterface $logger, TokenProcessor $tokenProcessor)
    {
        $this->container = $container;
        $this->tokenProcessor = $tokenProcessor;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $formData = $this->getFormData();
        $login = $formData->login;
        $password = $formData->password;

        $client = new GuzzleClient(['base_uri' => AUTH_URL, 'verify' => APP_ROOT . '/cacert.pem']);

        $data = ['app' => 'vm', 'login' => $login, 'password' => $password, 'decoded'  => 'f'];
        
        try{
            $response = $client->post('api/authenticate', [
                'json' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());

            if ($response->getStatusCode() == 201 && property_exists($payload, 'token') && $payload->type == 'auth') {                
                $token = $payload->token;
                $this->container->set('secureID', (int) $token->dist);
                $this->userRepository = $this->container->get(UserRepository::class);

                $user = $this->userRepository->findUserOfGlobalId((int) $token->gid);
                
                $token->id = $user->getId();
                $token->building = $user->getPrimaryTeamId();
                $token->redexp = null;
                $token->iat = null;
                $token->exp = null;

                $new_token = $this->tokenProcessor->create($token, 60*10, true);

                return $this->respondWithData(['token' => $new_token, 'tokenDecoded' => $token]);
            }
            else {
                throw new Exceptions\InternalServerErrorException();
            }
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            $fields = null;
            
            if (property_exists($response->error, 'fields') && is_array($response->error->fields)) {
                $fields = $response->error->fields;
            }
            throw new Exceptions\BadRequestException($response->error->userMessage, $fields);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\InternalServerErrorException($response->error->userMessage);
        }    

        return $this->respondWithData([]);        
    }
}
