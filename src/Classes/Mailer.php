<?php
namespace App\Classes;

use Aws\Ses\SesClient;
//use Aws\SesV2\SesClient;
use Aws\Exception\AwsException;
use Psr\Log\LoggerInterface;
use App\Exceptions;

class Mailer {
    private array $allowedAddresses = ['navigateprepared.com', 'navigatemail.com', 'navigate360.com', 'alicetraining.com'];
    
    private bool $devMode = false;
    private $SeSClient;
    private $PHPMailer = null;
    private $Emogrifier = null;
    
    function __construct(array $config, LoggerInterface $logger) {
        $this->SesClient = new SesClient($config['connection']);
        $this->devMode = $config['devMode'];
        $this->logger = $logger;
    }
    
    public function send($recipientEmails, $senderEmail, $subject, $htmlBody, $plainTextBody) {
        $charset = 'UTF-8';

        if ($this->devMode) {
            $actualRecipients = [];

            foreach ($recipientEmails as $recipient) {
                if (in_array(strtolower(substr($recipient, strpos($recipient, '@')+1)), $this->allowedAddresses)) {
                    $actualRecipients[] = $recipient;
                }
            }

            $recipientEmails = $actualRecipients;
        }

        try {
            $result = $this->SesClient->sendEmail([
                'Destination' => [
                    'ToAddresses' => $recipientEmails,
                ],
                'ReplyToAddresses' => [$senderEmail],
                'Source' => $senderEmail,
                'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => $charset,
                        'Data' => $htmlBody,
                    ],
                    'Text' => [
                        'Charset' => $charset,
                        'Data' => $plainTextBody,
                    ],
                ],
                'Subject' => [
                    'Charset' => $charset,
                    'Data' => $subject,
                ],
                ]
            ]);
            
            $messageId = $result['MessageId'];
            $this->logger->info("Email sent! Message ID: $messageId"."\n");
            return $messageId;
        } catch (AwsException $e) {
            // output error message if fails
             $this->logger->error("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
            throw new Exceptions\InternalServerErrorException($e->getMessage());
        }
    }
?>
