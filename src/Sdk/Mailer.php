<?php
namespace App\Sdk;

use App\Sdk\ServiceTrait;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    use ServiceTrait;

    /**
     * @var swift mailer service object
     */
    protected $swiftMailer;

    /**
     * @var templating engine service object
     */
    protected $templating;

    /**
     * @var from string email come from address
     */
    protected $from;

    public function __construct(Swift_Mailer $swiftMailer, EngineInterface $templating)
    {
        $this->swiftMailer = $swiftMailer;
        $this->templating = $templating;
        $this->from = $_ENV['MAIL_FROM_ADDRESS'];
    }

    /**
     * Sends an HTML email
     *
     * @param string $toAddress Receiver email address
     * @param string $subject Subject of the message
     * @param string $view Name of the view
     * @param array $data Data to be passed to the view
     */
    public function send($toAddress, $subject, $view, $data = [])
    {
        try {
            $message = (new \Swift_Message($subject))
            ->setFrom($this->from)
            ->setTo($toAddress)
            ->setBody(
                $this->templating->renderResponse($view, $data)->getContent(), 
                'text/html'
            );

            $this->swiftMailer->send($message);
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
