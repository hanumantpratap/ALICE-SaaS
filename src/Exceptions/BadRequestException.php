<?php
namespace App\Exceptions;

class BadRequestException extends AppException
{
    /**
     * @var array
     */
    protected $fields = null;

    protected $statusCode = 400;
    protected $type = 'BAD_REQUEST';
    protected $message = 'The server cannot or will not process the request due to an apparent client error.';

     /**
     * @param string $message
     * @param array  $fields
     */
    public function __construct(string $message = null, array $fields = null) {
        if ($message !== null) {
            $this->message = $message;
        }

        if ($fields !== null && is_array($fields)) {
            $this->fields = $fields;
        }

        parent::__construct($this->message, $this->statusCode, null);
    }

     /**
     * @return array|null
     */
    public function getFields()
    {
        return $this->fields;
    }
}
?>