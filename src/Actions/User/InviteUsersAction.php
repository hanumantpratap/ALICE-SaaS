<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Exceptions;
use App\Actions\Action;
use App\Classes\Mailer;
use App\Classes\TokenProcessor;

class InviteUsersAction extends Action
{
     /**
     * @param LoggerInterface $logger
     * @param Mailer $mailer
     * @param TokenProcessor $tokenProcessor
     */

    public function __construct(LoggerInterface $logger, Mailer $mailer, TokenProcessor $tokenProcessor)
    {
        parent::__construct($logger);
        $this->Mailer = $mailer;
        $this->tokenProcessor = $tokenProcessor;
    }

    protected function action(): Response
    {
        
        return $this->respondWithData([]);
    }
}
