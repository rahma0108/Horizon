<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controller\UtilisateurC;
use App\Model\Utilisateur;
require_once __DIR__ . '/../model/Utilisateur.php';
require_once __DIR__ . '/../controller/UtilisateurC.php';

// Instanciation du contrôleur
$utilisateurC = new UtilisateurC();

if (!isset($_GET['id'])) {
    die('ID utilisateur non spécifié');
}

$oldId = $_GET['id'];
$userData = $utilisateurC->getUtilisateurs($oldId);
if (!$userData) {
    die("Utilisateur non trouvé.");
}

$message = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newId = isset($_POST['id']) ? trim($_POST['id']) : '';
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    // Validation basique
    if (empty($newId) || empty($nom) || empty($prenom) || empty($email) || empty($adresse) || empty($date) || empty($role)) {
        $errors[] = "❌ Tous les champs sont obligatoires (sauf le mot de passe).";
    }

    // Validation de l'ID : doit contenir exactement 8 chiffres
    if (!preg_match('/^\d{8}$/', $newId)) {
        $errors[] = "❌ L'ID doit contenir exactement 8 chiffres.";
    }

    // Validation du nom : ne doit contenir que des lettres
    if (!preg_match('/^[a-zA-Z]+$/', $nom)) {
        $errors[] = "❌ Le nom doit contenir uniquement des lettres.";
    }

    // Validation du prénom : ne doit contenir que des lettres
    if (!preg_match('/^[a-zA-Z]+$/', $prenom)) {
        $errors[] = "❌ Le prénom doit contenir uniquement des lettres.";
    }

    // Validation du mot de passe (si fourni) : doit contenir des lettres et des chiffres
    if (!empty($password) && !preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $password)) {
        $errors[] = "❌ Le mot de passe doit contenir des lettres et des chiffres.";
    }

    // Validation de l'email : doit contenir un '@'
    if (!preg_match('/@/', $email)) {
        $errors[] = "❌ L'email doit contenir un '@'.";
    }

    // Validation du rôle : doit être 'user' ou 'admin'
    if (!in_array($role, ['user', 'admin'])) {
        $errors[] = "❌ Le rôle doit être 'user' ou 'admin'.";
    }

    if (empty($errors)) {
        // Gérer le mot de passe : conserver l'ancien si vide, hacher le nouveau si fourni
        $finalPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $userData['password'];

        // Création d'un objet Utilisateur avec le mot de passe approprié
        $updatedUser = new Utilisateur($newId, $nom, $prenom, $email, $adresse, $finalPassword, $date, $role);

        try {
            $utilisateurC->updateUtilisateurs($oldId, $updatedUser, $newId);
            $userData = $utilisateurC->getUtilisateurs($newId);
            $message = "✅ Utilisateur mis à jour avec succès ! Redirection dans 3 secondes...";
            echo "<meta http-equiv='refresh' content='3;url=backutilisateur.php'>";
        } catch (Exception $e) {
            $errors[] = "❌ Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur - GreenMove</title>
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

        h1 {
            color: #222;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="date"],
        .form-container select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container input[type="submit"] {
            margin-top: 20px;
            background-color: #222;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .form-container input[type="submit"]:hover {
            background-color: white;
            color: #222;
            border: 1px solid #222;
        }

        .message {
            background-color: #d4edda;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 5px;
            color: green;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 5px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>WELCOME</h2>
        <a href="#">Dashboard</a>
        <a href="backutilisateur.php">Users</a>
        <a href="#">Reservation</a>
        <a href="#">Events</a>
        <a href="#">Reports</a>
        <a href="#">Reclamations</a>
        <a href="#">Shop Details</a>
        <a href="#">Settings</a>
        <a href="#">Logout</a>
    </div>

    <div class="main-content">
        <h1>Modifier Utilisateur</h1>

        <?php if (!empty($message)): ?>
            <div class="message">
                <strong><?= htmlspecialchars($message) ?></strong>
                <h3>Informations mises à jour :</h3>
                <ul>
                    <li><strong>ID :</strong> <?= htmlspecialchars($userData['id']) ?></li>
                    <li><strong>Nom :</strong> <?= htmlspecialchars($userData['nom']) ?></li>
                    <li><strong>Prénom :</strong> <?= htmlspecialchars($userData['prenom']) ?></li>
                    <li><strong>Email :</strong> <?= htmlspecialchars($userData['email']) ?></li>
                    <li><strong>Adresse :</strong> <?= htmlspecialchars($userData['adresse']) ?></li>
                    <li><strong>Date de naissance :</strong> <?= htmlspecialchars($userData['date']) ?></li>
                    <li><strong>Rôle :</strong> <?= htmlspecialchars($userData['role']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="updateUtilisateur.php?id=<?= htmlspecialchars($oldId) ?>">
                <label for="id">ID :</label>
                <input type="text" id="id" name="id" value="<?= htmlspecialchars($userData['id']) ?>" required>

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($userData['nom']) ?>" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($userData['prenom']) ?>" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>

                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($userData['adresse']) ?>" required>

                <label for="password">Nouveau mot de passe (laisser vide pour ne pas modifier) :</label>
                <input type="password" id="password" name="password" placeholder="Saisir un nouveau mot de passe">

                <label for="date">Date de naissance :</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($userData['date']) ?>" required>

                <label for="role">Rôle :</label>
                <select id="role" name="role" required>
                    <option value="" <?= $userData['role'] === '' ? 'selected' : '' ?>>Sélectionner un rôle</option>
                    <option value="user" <?= $userData['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $userData['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>

                <input type="submit" value="Mettre à jour">
            </form>
        </div>
    </div>
</body>
</html>