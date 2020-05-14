<?php
declare(strict_types=1);

namespace App\Actions\User;

define("AUTH_URL", 'https://test-auth.navigatep.com/');

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Exception;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class SignInAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $login = $_POST["login"];
        $password = $_POST["password"];

        $client = new GuzzleClient(['base_uri' => AUTH_URL, 'verify' => APP_ROOT . '/cacert.pem']);

 
        $data = ['app' => 'vm', 'login' =>$login, 'password' => $password];
        
        try{
            $response = $client->post('api/authenticate', [
                'json' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());
            
            if ($response->getStatusCode() == 201 && property_exists($payload, 'token') && $payload->type == 'auth') {
                //return $response->withStatus(200)->withJson($payload);
                return $this->respondWithData($payload);
            }
            else {
                throw new Exception('An unexpected error has occurred.');
            }
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exception($response->error->userMessage);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exception($response->error->userMessage);
        }
        catch(Exception $e){
            throw new Exception('An unexpected error has occurred.');
        }       

        return $this->respondWithData([]);        
    }
}
