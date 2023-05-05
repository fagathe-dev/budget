<?php 
namespace App\Mailer;

final class MailerEnum 
{
    
    public const NOTIFICATION_CONFIRM_ACCOUNT = 'notification_confirm_account'; # CONFIRMER INSCRIPTION UTILISATEUR
    public const NOTIFICATION_CONFIRM_PASSWORD_UPDATED = 'notification_confirm_password_updated'; # CONFIRMER CHANGEMENT DE MOT DE PASSE
    public const NOTIFICATION_CONFIRM_INFOS_UPDATED = 'notification_confirm_infos_updated'; # CONFIRMER MIS A JOUR DES INFOS USER
    public const NOTIFICATION_CONFIRM_EMAIL_UPDATED = 'notification_confirm_email_updated'; # CONFIRMER MIS A JOUR DE L'ADRESSE E-MAIL
    public const NOTIFICATION_CONFIRM_ACCOUNT_CREATED = 'notification_confirm_account_created'; # CONFIRMER CREATION DE COMPTE 
    public const ACTION_SEND_TOKEN_RESET_PASSWORD = 'action_send_token_reset_password'; # ENVOI DE JETON RESET MOT DE PASSE 
    public const ACTION_SEND_TOKEN_EMAIL_VERIFY = 'action_send_token_email_verify'; # ENVOI EMAIL VERIFICATION
    public const ACTION_SEND_TOKEN_EMAIL_UPDATED = 'action_send_token_email_updated'; # ENVOI JETON CONFIRMER NOUVELLE ADRESSE E-MAIL

}
