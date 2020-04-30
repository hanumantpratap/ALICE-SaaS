<?php
namespace App\Classes;

use \Firebase\JWT\JWT;
use Psr\Http\Message\RequestInterface;

class TokenProcessor {
    private $redis;
    private $environment;
    private $secret;
    private $error;

    private $processTokenOptions = [
        "secure" => true,
        "relaxed" => ["localhost", "127.0.0.1"],
        "environment" => ["HTTP_AUTHORIZATION", "REDIRECT_HTTP_AUTHORIZATION"],
        "algorithm" => ["HS256", "HS512", "HS384"],
        "header" => "Authorization",
        "check_headers" => true,
        "regexp" => "/Bearer\s+(.*)$/i",
        "check_cookies" => true,
        "name" => "token",
        "path" => null,
        "passthrough" => null,
        "callback" => null,
        "error" => null,
        "type" => null
    ];

    function __construct() {
        //$this->redis = $redis;
        $this->secret = "hereisanexamplesecret_v2";
    }

    public function create($params, $expire = null, $refresh = false) {
        if (!is_array($params)) {
            $params = [];
        }

        $default_params = [
            'iat' => time()        
        ];

        if ($expire && is_numeric($expire)) {
            if ($refresh) {
                $default_params['redexp'] = $expire;
            }
            else {
                $default_params['exp'] = $default_params['iat'] + $expire; //expiration is issued at time plus expire time in seconds
            }
        }

        $payload = array_merge($params, $default_params);

        $token = JWT::encode($payload, $this->secret);

        // if token needs to refresh, use redis to manage expiration
        if (isset($default_params['redexp'])) {
            $this->redis->set($token, 1, $default_params['redexp']);
        }

        return $token;
    }

    public function validate($token) {
        return true;
        
        // check if token has expired in redis
        if ($this->redis->exists($token->encoded)) {
            // no, restart expiration countdown
            $this->redis->set($token->encoded, 1, $token->redexp);
            return true;
        }
        else {
            $this->setError('Redis Token has expired.');
            return false;
        }
    }

    public function secret() {
        return $this->secret;
    }

    public function decode($token) {
        try {
            return JWT::decode(
                $token,
                $this->secret,
                array('HS256')
            );
        } catch (\Exception $exception) {
            $this->setError($exception->getMessage());
            return false;
        }
    }

    // finds and validates token from http request
    public function processRequestToken(RequestInterface $request, $options = array()) {
        // merge default options with passed in options
        $options = $this->hydrate($options);

        if (!is_null($options['type']) && !is_array($options['type'])) {
            $options['type'] = [$options['type']];
        }

        $scheme = $request->getUri()->getScheme();
        $host = $request->getUri()->getHost();

        $return = array(
            'message' => null,
            'token' => null
        );
        
        /* First check for token in headers, unless check_headers is set to false*/
        if ($options['check_headers']) {
            if (false !== $token = $this->fetchTokenFromHeader($request, $options)) {
                $decoded = $this->decode($token);
                
                // Failed to decode token, either it set expiration tags or user modified the original
                if (false === $decoded) {
                    $token = false;
                }
                // check if token is of right type (if type is specified) and for the right Application
                else if ( isset($options['type']) && (is_array($options['type']) && !in_array($decoded->type, $options['type']))) {
                    $this->setError('Token is the wrong type');
                    $token = false;
                    $decoded = false;
                }
            }
        }

        /* If token is not found, cannot be decoded, or is of the wrong type: Check cookies/params */
        if(false === $token || is_null($token)) {
            if (false === $token = $this->fetchTokenFromCookieAndParams($request, $options)) {
                $this->setError('Token not found.');
                return false;
            }

            // Failed to decode token, either it set expiration tags or user modified the original
            if (false === $decoded = $this->decode($token)) {
                return false;
            }

            // check if token is of right type (if type is specified) and for the right Application
            if ( (is_array($options['type']) && !in_array($decoded->type, $options['type']))) {
                $this->setError('Token is the wrong type');
                return false;
            }
        }
        
        $decoded->encoded = $token;

        /* If token uses redis to control expiration, check if key exists in redis */
        if (isset($decoded->redexp) && !$this->validate($decoded)) {
            return false;
        }

        return $decoded;
    }

    private function setError($msg) {
        $this->error = $msg;
    }

    public function getError() {
        return $this->error;
    }

    public function fetchTokenFromHeader(RequestInterface $request, $options) {
        $server_params = $request->getServerParams();
        $header = "";
        $message = "";

        /* Check for each given environment */
        foreach ((array) $options["environment"] as $environment) {
            if (isset($server_params[$environment])) {
                $message = "Using token from environment";
                $header = $server_params[$environment];
            }
        }

        /* Nothing in environment, try header instead */
        if (empty($header)) {
            $message = "Using token from request header";
            $headers = $request->getHeader($options["header"]);
            $header = isset($headers[0]) ? $headers[0] : "";
        }

        /* Try apache_request_headers() as last resort */
        if (empty($header) && function_exists("apache_request_headers")) {
            $message = "Using token from apache_request_headers()";
            $headers = apache_request_headers();
            $header = isset($headers[$options["header"]]) ? $headers[$options["header"]] : "";
        }

        if (preg_match($options["regexp"], $header, $matches)) {
            //$this->log(LogLevel::DEBUG, $message);
            return $matches[1];
        }

        return false;
    }

    public function fetchTokenFromCookieAndParams(RequestInterface $request, $options) {
        if ($options["check_cookies"]) {
            $cookie_params = $request->getCookieParams();
            
            if (isset($cookie_params[$options["name"]])) {
                /* $this->log(LogLevel::DEBUG, "Using token from cookie");
                $this->log(LogLevel::DEBUG, $cookie_params[$options["name"]]); */
                return $cookie_params[$options["name"]];
            };
        }

        $query_params = $request->getQueryParams();
        if (isset($query_params[$options["name"]])) {
            return $query_params[$options["name"]];
        }

        return false;
    }

    private function hydrate(array $data = [])
    {
        $options = $this->processTokenOptions;

        foreach ($data as $key => $value) {
            $options[$key] = $value;
        }

        return $options;
    }
}