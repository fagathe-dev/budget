<?php
namespace App\Mailer\Auth;
use App\Mailer\Email;
use App\Mailer\AbstractMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class AccountVerifyEmail extends AbstractMailer
{

    public function emit(Email $email):void 
    {
        extract($email->getData());
        $label = $email->getLabel();
        
        $this->send(
            (new TemplatedEmail)
                ->from(DEFAULT_EMAIL_SENDER)
                ->to($token->getUser()->getEmail())
                ->subject($email->getTemplate())
                ->htmlTemplate('emails/layout.html.twig')
                ->context(compact('token', 'label'))
        );
    }
}