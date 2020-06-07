<?php
declare(strict_types=1);

namespace App\Actions\Dev\Redis;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class RedisListAction extends RedisAction
{
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();
        $keys = $this->redis->keys($params['pattern'] ?? "*");
        return $this->respondWithData(['keys' => $keys]);
    }
}
