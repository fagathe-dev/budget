<?php
namespace App\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportException;

abstract class AbstractMailer 
{

    public function __construct(
        private MailerInterface $mailer
    ){}

    final protected function send (TemplatedEmail $email):void
    {
        try {
            $this->mailer->send($email);
            return;
        } catch (TransportException $e) {
            throw new TransportException('Une erreur s\'est produite lors de l\'envoi de mail : ' . $e->getMessage());
        }
    }
}