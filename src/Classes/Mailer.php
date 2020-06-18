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
    
    public function send($recipientEmails, $senderEmail, $subject, $htmlBody, $plainTextBody = null) {
        $charset = 'UTF-8';
        
        if ($plainTextBody === null) {
            $plainTextBody = $this->toFormattedPlainText($htmlBody);
        }
        
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

    
    private function toFormattedPlainText($body) {
        if (!$body || !strlen($body)) {
            return '';
        }

        $body = preg_replace("/>((?:\s|\r|\n)+)</", "><", $body); //remove space between html tags
        $body = preg_replace('/<a(?:(?!href=).)+href=("[^"]+"|\'[^\']+\')[^>]*>((?:(?!<\/a).)+)<\/a>/i', '$2 &lt;$1&gt;', $body); //replace a tags with new formating
        $body = preg_replace('/(?:&lt;"((?:(?!"&gt;).)+)"&gt;|&lt;\'((?:(?!\'&gt;).)+)\'&gt;)/', '&lt;$1$2&gt;', $body); //remove double and single quotes from new a tag formatting
        //$body = preg_replace('/(?:(<strong(?:(?!>).)*>)((?:(?!<\/|\*).)+)(\*?<\/strong>)|(<(?:(?!<|font\-weight).)+font\-weight:\s?bold;?(?:(?!>).)+>)((?:(?!<\/|\*).)+)(\*?<\/(?:(?!>).)+>))/i', '$1$4&ast;$2$5&ast;$3$6', $body); //emphasize bold text
        $body = preg_replace_callback('/(<h[1-6](?:(?!>).)*>)((?:(?!<\/).)+)(<\/h[1-6]>)/i', function($matches) { return $matches[1] . strtoupper($matches[2]) . $matches[3]; }, $body); //capitalize header tags for even more emphasize than bold text
        $body = preg_replace('/<(\/?p|\/?button|\/?h1|\/?h2|\/?h3|\/?h4|\/?h5|\/?h6|br\/?)>/i', "\r\n\r\n", $body); //add new lines in lieu of certain tags
        $body = preg_replace("/(\r\n)(\s+)(\r\n)/", "$1$3", $body); //remove space between new lines
        $body = preg_replace("/(\r\n\s+|\s+\r\n)/", "\r\n", $body); //remove trailing spaces from new lines
        $body = strip_tags($body); //strip remaining html tags
        $body = html_entity_decode($body); //replace html entities &lt; and &gt; with < and >

        return $body;
    }

}
?>
