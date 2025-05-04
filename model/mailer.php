<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MailService
{
    public function sendResetPasswordEmail($to, $code)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';         // À adapter selon ton fournisseur
            $mail->SMTPAuth = true;
            $mail->Username = 'molkaajengui@gmail.com'; // Ton email
            $mail->Password = 'boad veop nuuy spas';   // Un mot de passe d'application (pas ton mot de passe Gmail direct)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Expéditeur
            $mail->setFrom('molkaajengui@gmail.com', 'GreenMove');

            // Destinataire
            $mail->addAddress($to);

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Code de réinitialisation de mot de passe';
            $mail->Body    = "
                <h3>Code de réinitialisation</h3>
                <p>Voici votre code de vérification : <strong>$code</strong></p>
                <p>Ce code expirera dans 30 minutes.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
