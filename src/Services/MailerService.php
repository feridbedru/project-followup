<?php

namespace App\Services;

use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    public function sendEmail(MailerInterface $mailer, $receiver, $subject, $message)
    {

        $email = (new Email())
            ->from('ferid.bedru@mint.gov.et')
            ->to($receiver)
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
