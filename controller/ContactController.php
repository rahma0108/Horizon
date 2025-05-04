<?php
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../model/mailer.php';

$message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$mailToSend = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$btn = filter_input(INPUT_POST, "envoyer", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($btn) && $btn == "envoyer") {
    if (filter_var($mailToSend, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $mail = new PHPMailer(true);
        $result = EnvoieMail($mail, $mailToSend, $message);
        if ($result === true) {
            header("Location: ../view/contact.php?success=1");
            exit();
        } else {
            header("Location: ../view/contact.php?error=" . urlencode($result));
            exit();
        }
    } else {
        header("Location: ../view/contact.php?error=" . urlencode("Adresse email invalide ou message vide."));
        exit();
    }
}
?>