<?php
namespace App\Exceptions;

class ServiceUnavailableException extends AppException
{
    protected $statusCode = 503;
    protected $type = 'NOT_IMPLEMENTED';
    protected $message = 'The server does not support the functionality required to fulfill the request.';
}
?>