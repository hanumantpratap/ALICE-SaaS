<?php
namespace App\Exceptions;
use Exception;

abstract class AppException extends Exception {

    /**
     * @var string
    */
    protected $message = 'An internal error has occurred while processing your request.';

    /**
     * @var int
    */
    protected $statusCode = 500;

    /**
     * @var string
    */
    protected $type = 'SERVER_ERROR';

    /**
     * @param string $message
     */
    public function __construct(string $message = null) {
        if ($message !== null) {
            $this->message = $message;
        }

        parent::__construct($this->message, $this->statusCode, null);
    }

    /**
     * @return integer
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
}
?>