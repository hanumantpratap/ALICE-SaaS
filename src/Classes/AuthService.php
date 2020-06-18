<?php
namespace App\Classes;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Exceptions;

class AuthService {
    private $clientUrl;
    private $serviceToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0eXBlIjoic2VydmljZSIsImlhdCI6MTU5MTkyMTM1OH0.nXMKOW6XbcHVfYPi0gorSTQqfcV5bM9F0IYosHGspu8';

    function __construct(string $clientUrl, string $authUrl) {
        $this->guzzle = new GuzzleClient(['base_uri' => $authUrl]);
        $this->clientUrl = $clientUrl;
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
        $data = [
            'app' => 'vm', 
            'login' =>$login,
            'reset-link' => $this->clientUrl . '/reset-password',
            'expiration-link' => $this->clientUrl . '/signin'
        ];
        
        try{
            $response = $this->guzzle->post('api/password/emailsV2', [
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

    public function updatePassword(int $globalUserId, string $password) { 
        $data = [
            'app'=>'vm', 
            'new-password'=>$password
        ];
        
        $token = $this->serviceToken;
        
        try{
            $response = $this->guzzle->put("api/users/${globalUserId}/password", [
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

    public function sendWelcomeEmail(int $globalUserId) { 
        $data = [
            'app' => 'vm',
            'reset-link' => $this->clientUrl . '/reset-password',
            'expiration-link' => $this->clientUrl . '/signin'
        ];
        
        $token = $this->serviceToken;

        try{
            $response = $this->guzzle->post("api/users/${globalUserId}/welcome-emails", [
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

    public function createUser(string $email, ?string $password = null, int $districtId) { 
        $data = [
            'email' => $email, 
            'password '=> $password,
            'district_id' => $districtId
        ];
        
        $token = $this->serviceToken;
        
        try{
            $response = $this->guzzle->post('api/users', [
                'json' => $data,
                'headers' => ['Authorization' => "Bearer ${token}"]
            ]);

            $payload = json_decode($response->getBody()->getContents());
            
            $code = $response->getStatusCode();
            if ($code == 200) {
                //$newUser = true;
                return (int) $payload->global_user_id;
            }
            else if ($code == 303) { //303 - User already exists
                return (int) $payload->error->resource->id;
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