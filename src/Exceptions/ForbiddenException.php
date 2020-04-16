<?php
namespace App\Exceptions;

class ForbiddenException extends AppException
{
    protected $statusCode = 403;
    protected $type = 'INSUFFICIENT_PRIVILEGES';
    protected $message = 'You are not permitted to perform the requested operation.';
}
?>