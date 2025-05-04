<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$error_message = '';
$id = '';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

define('DIR', __DIR__ . '/../');
require_once DIR . 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $recaptcha_secret = '6LdFWSYrAAAAADGop4YPwm8DzEhORgz0SMUlUca8';
        $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
        $id = trim($_POST['id'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation des champs
        if (empty($id) || empty($password)) {
            $error_message = 'Veuillez remplir tous les champs.';
        } elseif (!preg_match('/^\d{8}$/', $id)) {
            $error_message = 'L\'ID doit contenir exactement 8 chiffres.';
        } else {
            if (empty($recaptcha_response)) {
                $error_message = 'Veuillez cocher le CAPTCHA.';
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http Tour_build_query([
                    'secret' => $recaptcha_secret,
                    'response' => $recaptcha_response
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $recaptcha_verify = curl_exec($ch);
                curl_close($ch);

                $recaptcha_verify_response = json_decode($recaptcha_verify);
                if (!$recaptcha_verify_response->success) {
                    $error_message = 'Erreur de validation du CAPTCHA : ' . implode(', ', $recaptcha_verify_response->{'error-codes'} ?? ['inconnu']);
                } else {
                    try {
                        require_once DIR . 'model/Databaseconfig.php';
                        $dbConfig = new App\Model\Databaseconfig();
                        $db = $dbConfig->getConnexion();
                        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE id = :id');
                        $stmt->execute([':id' => $id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($user && password_verify($password, $user['password'])) {
                            $_SESSION['authenticated'] = true;
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_role'] = $user['role']; // Stocker le rôle
                            $_SESSION['name'] = $user['name'];
                            header('Location: vide.php');
                            exit;
                        } else {
                            $error_message = 'Identifiant ou mot de passe incorrect.';
                        }
                    } catch (PDOException $e) {
                        $error_message = 'Erreur de connexion à la base de données.';
                        error_log('PDO Error: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-signin-client_id" content="82224683550-8md28f7lirmg50bj7ebj882pshvi8b6s.apps.googleusercontent.com">
    <title>Connexion - Votre Application</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <style>
        /* Style inchangé */
    </style>
</head>
<body>
<div class="login-container">
    <h2>Connexion Utilisateur</h2>
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <form method="POST" onsubmit="return validateRecaptcha()">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <label for="id">ID :</label>
        <input type="text" name="id" id="id" value="<?= htmlspecialchars($id) ?>" required>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <div class="g-recaptcha" data-sitekey="6LdFWSYrAAAAAEGLFcvTQBvOhbLjIdyAU-Zbm62p"></div>
        <input type="submit" name="submit" value="Valider">
    </form>
    <div class="forgot-password">
        <a href="forgot_password.php">Mot de passe oublié ?</a>
    </div>
    <div class="or-divider">OU</div>
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
        // Script inchangé
    </script>
</div>
</body>
</html>