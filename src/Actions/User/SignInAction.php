<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Log\LoggerInterface;
use App\Classes\TokenProcessor;
use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Container\ContainerInterface;
use App\Domain\User\UserRepository;
use App\Classes\AuthService;

class SignInAction extends Action
{
    /**
     * @param ContainerInterface $c
     * @param LoggerInterface $logger
     * @param TokenProcessor $tokenProcessor
     * @param AuthService $authService
     */

    public function __construct(ContainerInterface $container, LoggerInterface $logger, TokenProcessor $tokenProcessor, AuthService $authService)
    {
        $this->container = $container;
        $this->tokenProcessor = $tokenProcessor;
        $this->authService = $authService;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $formData = $this->getFormData();
        $login = $formData->login;
        $password = $formData->password;

        $payload = $this->authService->signIn($login, $password);
        $token = $payload->token;

        $this->container->set('secureID', (int) $token->dist);

        $this->userRepository = $this->container->get(UserRepository::class);
        $user = $this->userRepository->findUserOfGlobalId((int) $token->gid);
        $token->id = $user->getId();
        $token->building = $user->getPrimaryBuildingId();
        $token->redexp = null;
        $token->iat = null;
        $token->exp = null;

        // create auth tokens - 24 hour expiration
        $new_token = $this->tokenProcessor->create($token, 60*60*24, true);

        return $this->respondWithData(['token' => $new_token, 'tokenDecoded' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/sign-in",
     *     tags={"Sign-in"},
     *     @OA\Response(
     *         response=200,
     *         description="Sign In",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
}
