<?php
declare(strict_types=1);

namespace App\Actions\Dev\Redis;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;

class RedisSetAction extends RedisAction
{ 
    protected function action(): Response
    {
        $formData = $this->getFormData();

        if (!isset($formData->key) || !isset($formData->value)) {
            throw new Exceptions\BadRequestException();
        }

        $this->redis->set($formData->key, $formData->value, $formData->exp ?? 300);

        return $this->respondWithData(null, 201);
    }
}
