<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Model\Databaseconfig;
use App\Service\MailService;

require_once __DIR__ . '/../model/Databaseconfig.php';
require_once __DIR__ . '/mailservice.php';


session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    try {
        $dbConfig = new Databaseconfig();
        $db = $dbConfig->getConnexion();

        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $reset_code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $_SESSION['reset_code'] = $reset_code;
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_expire'] = time() + 1800; // 30 minutes

            $mailService = new MailService();
            if ($mailService->sendResetPasswordEmail($email, $reset_code)) {
                header('Location: verify_code.php');
                exit();
            } else {
                $error_message = "Échec de l'envoi de l'email. Veuillez réessayer.";
            }
        } else {
            $error_message = "Aucun compte associé à cet email.";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $error_message = "Une erreur technique est survenue.";
    } catch (Exception $e) {
        error_log("Mail error: " . $e->getMessage());
        $error_message = "Erreur d'envoi d'email. Contactez l'administrateur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <div class="auth-container">
        <h2>Réinitialisation du mot de passe</h2>
        
        <?php if ($error_message): ?>
            <div class="alert error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Adresse email :</label>
                <input type="email" name="email" required 
                       placeholder="exemple@greenmove.com">
            </div>
            
            <button type="submit" class="btn-primary">Envoyer le code de vérification</button>
        </form>
        
        <div class="links">
            <a href="front2_recaptcha.php">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
