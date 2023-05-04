<?php
namespace App\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

abstract class AbstractMailer 
{

    protected const DEFAULT_SENDER = 'contact@agathefrederick.fr';
    protected const ACCOUNT_CONFIRM_EMAIL = 'ACCOUNT_CONFIRM_EMAIL'; 
    protected const RESET_PASSWORD_TOKEN_EMAIL = 'RESET_PASSWORD_TOKEN_EMAIL'; 
    protected const PASSWORD_CHANGED_NOTIFICATION_EMAIL = 'PASSWORD_CHANGED_NOTIFICATION_EMAIL'; 
    protected const EMAIL_CHANGED_NOTIFICATION_EMAIL = 'EMAIL_CHANGED_NOTIFICATION_EMAIL'; 

    public function __construct(
        private MailerInterface $mailer
    ){}

    public function send (TemplatedEmail $email):void
    {
        try {
            $this->mailer->send($email);
            return;
        } catch (TransportExceptionInterface $e) {
            throw new TransportException("Something went wrong while sending this email : {$e->getMessage()}");
        }
    }
}