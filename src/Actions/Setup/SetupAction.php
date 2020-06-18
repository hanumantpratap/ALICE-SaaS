<?php
declare(strict_types=1);

namespace App\Actions\Setup;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\AuthService;
use App\Classes\TokenProcessor;
use Psr\Container\ContainerInterface;

abstract class SetupAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param AuthService $authService
     * @param ContainerInterface $container
     */

    public function __construct(LoggerInterface $logger, AuthService $authService, ContainerInterface $container, TokenProcessor $tokenProcessor)
    {
        parent::__construct($logger);
        $this->authService = $authService;
        $this->container = $container;
        $this->tokenProcessor = $tokenProcessor;
    }
}
