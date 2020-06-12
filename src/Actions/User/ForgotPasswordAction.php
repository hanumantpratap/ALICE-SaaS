<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Classes\AuthService;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ForgotPasswordAction extends Action
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
        $username = $formData->username;

        $this->authService->sendPasswordReset($username);  

        return $this->respondWithData(null, 201);        
    }
}
