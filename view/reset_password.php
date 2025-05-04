<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Model\Databaseconfig;
session_start();

$error_message = "";
$success_message = "";

if (isset($_POST['submit'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];

    // Validation du mot de passe : doit contenir des lettres et des chiffres
    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $new_password)) {
        $error_message = "❌ Le mot de passe doit contenir des lettres et des chiffres.";
    } elseif ($new_password === $confirm_password) {
        try {
            // Hacher le mot de passe
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $dbConfig = new Databaseconfig();
            $db = $dbConfig->getConnexion(); 
            $stmt = $db->prepare('UPDATE utilisateurs SET password = :password WHERE email = :email');
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $success_message = "Mot de passe réinitialisé avec succès.";
            unset($_SESSION['reset_code']);
            unset($_SESSION['reset_email']);
            header("Location: front2_recaptcha.php");
            exit();
        } catch (PDOException $e) {
            $error_message = "Erreur: " . $e->getMessage();
        }
    } else {
        $error_message = "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe - GreenMove</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }

        .reset-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 100px auto;
        }

        h2 {
            text-align: center;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Réinitialisation du mot de passe</h2>

    <form method="POST">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" name="new_password" id="new_password" required>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <input type="submit" name="submit" value="Réinitialiser">
    </form>

    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?= htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
</div>

</body>
</html>