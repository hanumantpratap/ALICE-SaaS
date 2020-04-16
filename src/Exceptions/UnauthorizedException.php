<?php
namespace App\Exceptions;

class UnauthorizedException extends AppException
{
    protected $statusCode = 401;
    protected $type = 'INSUFFICIENT_PRIVILEGES';
    protected $message = 'The request requires valid user authentication.';
}
?>