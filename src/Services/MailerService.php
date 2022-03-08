<?php

namespace App\Services;

use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class MailerService
{

    public function sendEmail(MailerInterface $mailer, $receiver, $subject, $message, $cc=NULL)
    {
        $email = (new Email())
            ->from('project.followup@mint.gov.et')
            ->to(...$receiver);
            if($cc){
                $email->cc($cc);
            }
            $email
            ->subject($subject)
            ->html($message);
        try {
            $mailer->send($email);
        } catch (Exception $e) {
            return 0;
        }

        return 1;
    }
}
