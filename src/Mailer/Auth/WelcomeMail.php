<?php
namespace App\Mailer\Auth;

use App\Entity\User;
use App\Mailer\AbstractMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class WelcomeMail extends AbstractMailer
{

    public function confirmRegistration(User $user):void 
    {
        $this->send(
            (new TemplatedEmail)
                ->from(DEFAULT_EMAIL_SENDER)
                ->to($user->getEmail())
                ->subject('Xpense: Confirmation de votre compte')
                ->htmlTemplate('emails/layout.html.twig')
                ->context(compact('user'))
        );
    }

}