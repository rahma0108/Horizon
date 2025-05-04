<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controller\UtilisateurC;
use App\Model\Utilisateur;
use App\Model\Databaseconfig;

// Initialisation des variables pour conserver les valeurs saisies
$id = $nom = $prenom = $email = $adresse = $date = $password = '';
$role = ''; // Pas de rôle par défaut, sera défini par le formulaire
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et stocker les données du formulaire
    $id = isset($_POST['id_utilisateur']) ? trim($_POST['id_utilisateur']) : '';
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    // Validation basique
    if (empty($id) || empty($nom) || empty($prenom) || empty($email) || empty($adresse) || empty($password) || empty($date) || empty($role)) {
        $errors[] = "❌ Tous les champs sont obligatoires.";
    }

    // Validation de l'ID : doit contenir exactement 8 chiffres
    if (!preg_match('/^\d{8}$/', $id)) {
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

    // Validation du mot de passe : doit contenir des lettres et des chiffres
    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $password)) {
        $errors[] = "❌ Le mot de passe doit contenir des lettres et des chiffres.";
    }

    // Validation de l'email : format valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "❌ L'email doit être valide.";
    }

    // Validation de la date : format YYYY-MM-DD
    if (!empty($date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "❌ La date doit être au format YYYY-MM-DD.";
    }

    // Validation du rôle : doit être "user" ou "admin"
    if (!in_array($role, ['user', 'admin'])) {
        $errors[] = "❌ Le rôle doit être 'user' ou 'admin'.";
    }

    // Si aucune erreur jusqu'ici, vérifier l'unicité de l'ID et de l'email
    if (empty($errors)) {
        try {
            $utilisateurC = new UtilisateurC();
            $existingUser = $utilisateurC->getUtilisateurs($id);
            if ($existingUser) {
                $errors[] = "❌ Cet ID existe déjà. Choisissez un autre.";
            }

            $db = Databaseconfig::getConnexion();
            $stmt = $db->prepare("SELECT email FROM utilisateurs WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "❌ Cet email est déjà utilisé. Choisissez un autre.";
            }

            // Si aucune erreur, ajouter l'utilisateur
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $newUser = new Utilisateur($id, $nom, $prenom, $email, $adresse, $hashedPassword, $date, $role);
                $utilisateurC->addUtilisateurs($newUser, $id);
                $message = "✅ Utilisateur ajouté avec succès ! Redirection dans 3 secondes...";
                // Stocker l'ID et le rôle dans la session
                $_SESSION['last_inserted_id'] = $id;
                $_SESSION['last_inserted_role'] = $role;
                // Redirection vers backutilisateur.php
                header('Location: backutilisateur.php?success=' . urlencode($message));
                exit();
            }
        } catch (Exception $e) {
            $errors[] = "❌ Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur - GreenMove</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #c5ff38;
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

        form {
            background-color: white;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            background-color: #222;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        button:hover {
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
    <h1>Ajouter un Utilisateur</h1>

    <?php if (!empty($message)): ?>
        <div class="message">
            <strong><?= htmlspecialchars($message) ?></strong>
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

    <form method="POST" action="front.php">
        <label for="id_utilisateur">ID :</label>
        <input type="text" name="id_utilisateur" id="id_utilisateur" value="<?= htmlspecialchars($id) ?>" required>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($nom) ?>" required>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($prenom) ?>" required>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($adresse) ?>" required>

        <label for="date">Date de Naissance :</label>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($date) ?>" required>

        <label for="password">Mot de Passe :</label>
        <input type="password" name="password" id="password" value="<?= htmlspecialchars($password) ?>" required>

        <label for="role">Rôle :</label>
        <select name="role" id="role" required>
            <option value="" <?= $role === '' ? 'selected' : '' ?>>Sélectionner un rôle</option>
            <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</div>

</body>
</html>