<?php
declare(strict_types=1);

namespace App\Actions\Dev\Redis;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\RedisConnector;

abstract class RedisAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param RedisConnector $redis
     */

    public function __construct(LoggerInterface $logger, RedisConnector $redis)
    {
        $this->logger = $logger;
        $this->redis = $redis;
    }
}
