<?php
namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';



class MailService {
    public function sendResetPasswordEmail($to, $code) {
        $subject = "Réinitialisation de votre mot de passe";
        $body = "
            <p>Bonjour,</p>
            <p>Voici votre code de réinitialisation : <strong>$code</strong></p>
            <p>Ce code est valable pendant 30 minutes.</p>
            <p>Merci de ne pas répondre à cet email.</p>
        ";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'molkaajengui@gmail.com'; // Remplace
            $mail->Password   = '  
boad veop nuuy spas';     // Mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('molkaajengui@gmail.com', 'GreenMove'); // Remplace
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Erreur d’envoi mail : ' . $e->getMessage());
            return false;
        }
    }
}
