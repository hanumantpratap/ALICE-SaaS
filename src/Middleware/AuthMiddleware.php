<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {   
        $this->container = $container;
        $this->logger = $logger;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->logger->info('user auth');

        /* $token =  $request->getAttribute('token');

        // Validate Auth Token
        if (!$token || $token->type != 'auth') {
            throw new \App\Exceptions\UnauthorizedException();
        }
        
        $this->container->set('secureID', $token->dist);
        $request = $request->withAttribute('secureID', $token->dist); */

        $this->container->set('secureID', '5235');

        return $handler->handle($request);
    }
}

?>