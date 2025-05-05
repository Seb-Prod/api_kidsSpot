<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclusion manuelle des classes PHPMailer
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/Exception.php';

function envoyerEmail($destinataire, $sujet, $contenuHTML, $contenuTexte = '')
{
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kidspottp@gmail.com';
        $mail->Password = 'zcnw vpsy yobn ayis'; // mot de passe d’application (non le mot de passe Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Paramètres de l'email
        $mail->setFrom('kidspottp@gmail.com', 'Kids Spot');
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