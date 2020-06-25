<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Classes\AuthService;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ResetPasswordAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param AuthService $authService
     */

    public function __construct(LoggerInterface $logger, AuthService $authService)
    {
        $this->authService = $authService;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $formData = $this->getFormData();
        $token= $formData->token;
        $newPassword= $formData->password;
        $repeatNewPassword= $formData->repeat_password;

        $this->authService->resetPassword($token, $newPassword, $repeatNewPassword);
        return $this->respondWithData(null, 200);        
    }
}
 /**
     * @OA\Post(
     *     path="/reset-password",
     *     tags={"reset-password"},
     *     @OA\Response(
     *         response=200,
     *         description="Reset Password",
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