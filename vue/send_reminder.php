<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // adapte si besoin

// Connexion DB
$pdo = new PDO("mysql:host=localhost;dbname=evenement;charset=utf8", 'root', '');

// Sélection des réservations dans l'heure
$sql = "
    SELECT u.email, e.event_date,e.title
    FROM reservations r
    JOIN user u ON r.user_id = u.user_id
    JOIN events e ON r.event_id = e.id
    WHERE r.status = 'confirmed'
    AND TIMESTAMPDIFF(MINUTE, NOW(), e.event_date) BETWEEN 0 AND 60
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($reservations as $res) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres SMTP Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bizbiz1478@gmail.com'; // remplace par ton mail Gmail
        $mail->Password = 'zrre xicg plfp tfmb'; // 16 chars app password (pas ton mot de passe Gmail normal)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email
        $mail->setFrom('bizbiz1478@gmail.com', 'Event Reminder');
        $mail->addAddress($res['email']);
        $mail->Subject = " Rappel : votre evenement ". $res['title'] ." commence bientot !";
        $mail->Body = "Bonjour, ceci est un rappel que votre événement commence à " . $res['event_date'];

        $mail->send();
        echo "✅ Email envoyé à " . $res['email'] . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur email " . $res['email'] . " : {$mail->ErrorInfo}\n";
    }
}
