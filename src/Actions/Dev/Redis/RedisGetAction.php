<?php
declare(strict_types=1);

namespace App\Actions\Dev\Redis;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;

class RedisGetAction extends RedisAction
{
    protected function action(): Response
    {
        $key = (string) $this->resolveArg('key');

        if (!$this->redis->exists($key)) {
            throw new Exceptions\NotFoundException();
        }

        $value = $this->redis->get($key);
        $expiration = (string) $this->redis->getExpire($key) . ' seconds';

        return $this->respondWithData(['value' => $value, 'expiration' => $expiration]);
    }
}
