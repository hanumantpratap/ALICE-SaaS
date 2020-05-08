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
        
        // for now, hardcoding a token in so we can test app
        if (!$token) {
            $token = new \stdClass();

            $token->type = 'auth';
            $token->id = '200000127';
            $token->gid = '65';
            $token->dist = '5235';
            $token->admin = 'f';
        }

        $request = $request->withAttribute('token', $token);

        return $handler->handle($request);
    }
}

?>
