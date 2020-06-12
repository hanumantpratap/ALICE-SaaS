<?php
namespace App\Classes;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Exceptions;

class AuthService {
    private $authEndpoint = 'https://test-auth.navigatep.com/';
    private $serviceToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0eXBlIjoic2VydmljZSIsImlhdCI6MTU5MTkyMTM1OH0.nXMKOW6XbcHVfYPi0gorSTQqfcV5bM9F0IYosHGspu8';

    function __construct() {
        $this->guzzle = new GuzzleClient(['base_uri' => $this->authEndpoint]);
    }

    public function signIn(string $login, string $password) {
        $formData = [
            'app' => 'vm', 
            'login' => $login, 
            'password' => $password, 
            'decoded'  => 'f']
        ;

        try{
            $response = $this->guzzle->post('api/authenticate', [
                'json' => $formData
            ]);

            $payload = json_decode($response->getBody()->getContents());

            if ($response->getStatusCode() == 201 && property_exists($payload, 'token') && $payload->type == 'auth') {         
                return $payload;
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
    }

    public function sendPasswordReset(string $login) { 
        $data = ['app' => 'np', 'login' =>$login];
        
        try{
            $response = $this->guzzle->post('api/password/emails', [
                'json' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());
            
            if ($response->getStatusCode() == 200) {
                return $payload;
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
    }

    public function resetPassword($token, $newPassword, $repeatNewPassword) { 
        $data = [
            'app'=>'np', 
            'new_password'=>$newPassword, 
            'repeat_new_password'=>$repeatNewPassword 
        ];
        
        try{
            $response = $this->guzzle->put('api/password/reset', [
                'json' => $data,
                'headers' => ['Authorization' => "Bearer ${token}"]
            ]);

            $payload = json_decode($response->getBody()->getContents());
            
            if ($response->getStatusCode() == 200) {
                return $payload;
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
    }

    public function createUser($email, $password) { 
        $data = [
            'email'=>$email, 
            'password'=>$password 
        ];
        
        try{
            $response = $this->guzzle->post('api/users', [
                'json' => $data,
                'headers' => ['Authorization' => "Bearer ${token}"]
            ]);

            $payload = json_decode($response->getBody()->getContents());
            
            if ($response->getStatusCode() == 200) {
                return $payload;
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
    }
}