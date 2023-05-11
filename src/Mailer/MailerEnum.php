<?php 
namespace App\Mailer;

final class MailerEnum 
{
    
    public const NOTIFICATION_ACTIVATE_ACCOUNT = 'NOTIFICATION_ACTIVATE_ACCOUNT'; # CONFIRMER INSCRIPTION UTILISATEUR
    public const NOTIFICATION_CONFIRM_PASSWORD_UPDATED = 'NOTIFICATION_CONFIRM_PASSWORD_UPDATED'; # CONFIRMER CHANGEMENT DE MOT DE PASSE
    public const NOTIFICATION_CONFIRM_INFOS_UPDATED = 'NOTIFICATION_CONFIRM_INFOS_UPDATED'; # CONFIRMER MIS A JOUR DES INFOS USER
    public const NOTIFICATION_CONFIRM_EMAIL_UPDATED = 'NOTIFICATION_CONFIRM_EMAIL_UPDATED'; # CONFIRMER MIS A JOUR DE L'ADRESSE E-MAIL
    public const NOTIFICATION_CONFIRM_ACCOUNT_CREATED = 'NOTIFICATION_CONFIRM_ACCOUNT_CREATED'; # CONFIRMER CREATION DE COMPTE 
    public const NOTIFICATION_CONFIRM_REGISTRATION = 'NOTIFICATION_CONFIRM_REGISTRATION'; # CONFIRMER CREATION DE COMPTE 
    public const ACTION_SEND_TOKEN_RESET_PASSWORD = 'ACTION_SEND_TOKEN_RESET_PASSWORD'; # ENVOI DE JETON RESET MOT DE PASSE 
    public const ACTION_SEND_TOKEN_ACCOUNT_VERIFY = 'ACTION_SEND_TOKEN_ACCOUNT_VERIFY'; # ENVOI EMAIL VERIFICATION
    public const ACTION_SEND_TOKEN_EMAIL_VERIFY = 'ACTION_SEND_TOKEN_EMAIL_VERIFY'; # ENVOI EMAIL VERIFICATION
    public const ACTION_SEND_TOKEN_EMAIL_UPDATE = 'ACTION_SEND_TOKEN_EMAIL_UPDATE'; # ENVOI JETON CONFIRMER NOUVELLE ADRESSE E-MAIL
        
    /**
     * getEmail
     *
     * @param  mixed $email
     * @return Email
     */
    public static function getEmail(?string $email):?Email 
    {
        if (array_key_exists($email, static::getEmails())) {
            return static::getEmails()[$email];
        }

        return null;
    }

    /**
     * getEmails
     *
     * @return Email[]
     */
    public static function getEmails(): array
    {
        return [
            self::ACTION_SEND_TOKEN_EMAIL_UPDATE => new Email(
                'Confirmation de votre adresse e-mail', 
                'action/email_update.html.twig',
                ['user', 'email']
            ),
            self::ACTION_SEND_TOKEN_EMAIL_VERIFY => new Email(
                'Vérification de votre nouvelle adresse e-mail', 
                'action/email_verify.html.twig',
                ['user']
            ),
            self::ACTION_SEND_TOKEN_ACCOUNT_VERIFY => new Email(
                'Vérification de votre compte', 
                'action/account_verify.html.twig',
                ['token']
            ),
            self::ACTION_SEND_TOKEN_RESET_PASSWORD => new Email(
                'Réinitialiser votre mot de passe', 
                'action/reset_password.html.twig',
                ['user', 'email']
            ),
            self::NOTIFICATION_CONFIRM_ACCOUNT_CREATED => new Email(
                'Création de votre compte', 
                'notification/account_created.html.twig',
                ['user']
            ),
            self::NOTIFICATION_CONFIRM_REGISTRATION => new Email(
                'Création de votre compte', 
                'notification/account_registration.html.twig',
                ['user']
            ),
            self::NOTIFICATION_CONFIRM_INFOS_UPDATED => new Email(
                'Modification de votre compte', 
                'notification/infos_updated.html.twig',
                ['user']
            ),
            self::NOTIFICATION_CONFIRM_EMAIL_UPDATED => new Email(
                'Modification de votre adresse e-mail', 
                'notification/email_updated.html.twig',
                ['user']
            ),
            self::NOTIFICATION_CONFIRM_PASSWORD_UPDATED => new Email(
                'Modification de votre mot de passe', 
                'notification/password_updated.html.twig',
                ['user']
            ),
            self::NOTIFICATION_ACTIVATE_ACCOUNT => new Email(
                'Confirmation de votre compte', 
                'notification/account_confirm.html.twig',
                ['user']
            ),
        ];
    }

}
