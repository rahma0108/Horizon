<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    error_log('Utilisateur non authentifié. Redirection vers front2_recaptcha.php');
    header("Location: front2_recaptcha.php");
    exit();
}

// Récupérer le rôle de la session (le nom n'est plus nécessaire)
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user'; // Utiliser user par défaut
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - GreenMove</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: #222;
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: white;
            color: black;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
            min-height: 100vh;
        }

        .confirmation-message {
            background-color: white;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .confirmation-message h2 {
            color: #222;
            margin-bottom: 20px;
        }

        .confirmation-message a, .confirmation-message button {
            display: inline-block;
            margin-top: 20px;
            background-color: #222;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin-right: 10px;
        }

        .confirmation-message a:hover, .confirmation-message button:hover {
            background-color: white;
            color: #222;
            border: 1px solid #222;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="backutilisateur.php">Gestion des utilisateurs</a>
        <a href="front2_recaptcha.php">Déconnexion</a>
    </div>

    <div class="main-content">
        <div class="confirmation-message">
            <h2>Bienvenue !</h2>
            <?php if ($userRole === 'admin'): ?>
                <button onclick="window.location.href='backutilisateur.php'">Voir la liste des utilisateurs</button>
            <?php endif; ?>
            <a href="templateModifier.php">modifier</a>
            <a href="front2_recaptcha.php">Se déconnexion</a>
        </div>
    </div>
</body>
</html>