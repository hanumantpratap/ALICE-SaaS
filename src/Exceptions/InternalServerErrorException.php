<?php
namespace App\Exceptions;

class InternalServerErrorException extends AppException
{
    protected $statusCode = 500;
    protected $type = 'SERVER_ERROR';
    protected $message = 'Unexpected condition encountered preventing server from fulfilling request.';
}
?>