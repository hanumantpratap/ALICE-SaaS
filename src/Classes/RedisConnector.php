<?php
namespace App\Classes;

use Predis\Client as Predis;
use Predis\Connection\PhpiredisStreamConnection;

//Note: predis/predis suggests installing ext-phpiredis (Allows faster serialization and deserialization of the Redis protocol)
//https://github.com/nrk/predis/blob/v1.1/README.md#customizable-connection-backends

class RedisConnector {
    private $redis;

    function __construct($config) {
        $this->redis = new Predis(array(
                            "scheme" => "tcp",
                            "host" => $config['host'],
                            "port" => $config['port']));
                            
        /*
            $this->redis = new Predis\Client([
                            "scheme" => "tcp",
                            "host" => "127.0.0.1",
                            "port" => 5530], 
                            ['connections' => ['tcp' => 'Predis\Connection\PhpiredisStreamConnection']]);
            
    	*/
    }

    // return value of a key
    public function get($key) {
        return $this->redis->get($key);
    }

    // return time left (in seconds) for a key
    public function getExpire($key) {
        return $this->redis->ttl($key);
    }

    // store a value with an (optional) expiration in seconds
    public function set($key, $value, $exp = null) {
        if ($exp != null) {
            $this->redis->setex($key, $exp, $value);
        }
        else {
            $this->redis->set($key, $value);
        }
    }

    // return a list of keys by a (optional) pattern
    public function keys($pattern = '*') {
        return $this->redis->keys($pattern);
    }

    // check if a key exists
    public function exists($key) {
        return $this->redis->exists($key);
    }

    // set expiration for an existing key
    public function expire($key, $value) {
        $this->redis->expire($key, $value);
    }

    // sets the value for a key on the the hash object
    public function hashSet($hash, $key, $value) {
        $this->redis->hset($hash, $key, $value);
    }

    // gets the value for a key on the hash object
    public function hashGet($hash, $key) {
        return $this->redis->hget($hash, $key);
    }

    // set array of key value pairs on the hash object
    public function hashArray($hash, $array) {
        $this->redis->hmset($hash, $array);
    }

    // remove a key from the object
    public function hashDelete($hash, $key) {
        $this->redis->hdel($hash, $key);
    }

    // get all keys and data for a object
    public function hashGetAll($hash) {
        return $this->redis->hgetall($hash);
    }

    // delete a key
    public function delete($key) {
        $this->redis->del($key);
    }

    public function connect() {
        $this->redis->connect();
    }
}