<?php
namespace App\Exceptions;

class NotFoundException extends AppException
{
    protected $statusCode = 404;
    protected $type = 'RESOURCE_NOT_FOUND';
    protected $message = 'The requested resource could not be found. Please verify the URI and try again.';
}
?>