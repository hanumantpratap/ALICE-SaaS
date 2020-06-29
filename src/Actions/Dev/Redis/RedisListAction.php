<?php
declare(strict_types=1);

namespace App\Actions\Dev\Redis;

use Psr\Http\Message\ResponseInterface as Response;

class RedisListAction extends RedisAction
{
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();
        $keys = $this->redis->keys($params['pattern'] ?? "*");

        if ($params['detailed'] == 't') {
            $detailedKeys = [];
            foreach ($keys as $key) {
                $detailedKeys[] = [
                    'key' => $key,
                    'value' => $this->redis->get($key),
                    'expiration' =>  (string) $this->redis->getExpire($key) . ' seconds'
                ];
            }

            $keys = $detailedKeys;
        }

        return $this->respondWithData(['keys' => $keys]);
    }
}
