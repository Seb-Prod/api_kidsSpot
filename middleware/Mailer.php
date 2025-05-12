<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclusion manuelle des classes PHPMailer
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/Exception.php';

function envoyerEmail($destinataire, $sujet, $contenuHTML, $contenuTexte = '')
{
    $config = require __DIR__ . '/../config/config.php';
    $mailConfig = $config['mail'];
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $mailConfig['smtp_host'];
        $mail->SMTPAuth = $mailConfig['smtp_auth'];
        $mail->Username = $mailConfig['smtp_username'];
        $mail->Password = $mailConfig['smtp_password'];
        $mail->Port = $mailConfig['smtp_port'];

        $secure = $mailConfig['smtp_secure'];

        switch ($secure) {
            case 'tls':
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                break;
            case 'ssl':
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                break;
            default:
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }



        $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
        $mail->addAddress($destinataire);

        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body    = $contenuHTML;
        $mail->AltBody = $contenuTexte ?: strip_tags($contenuHTML);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email : " . $mail->ErrorInfo);
        return false;
    }
}
