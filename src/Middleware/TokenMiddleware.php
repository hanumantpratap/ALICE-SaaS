<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use App\Classes\TokenProcessor;

class TokenMiddleware implements MiddlewareInterface
{
    public function __construct(LoggerInterface $logger, TokenProcessor $tokenProcessor)
    {
        $this->logger = $logger;
        $this->tokenProcessor = $tokenProcessor;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->logger->info('process token');

        $token = $this->tokenProcessor->processRequestToken($request, ['secure' => false]);
        $request = $request->withAttribute('token', $token);

        return $handler->handle($request);
    }
}

?>
