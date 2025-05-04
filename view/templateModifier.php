<?php
// Démarrer la session
session_start();

// Inclure l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Importer les classes avec leurs namespaces
use App\Model\Utilisateur;
use App\Controller\UtilisateurC;
use App\Model\Databaseconfig;

// Vérifier si l'ID est spécifié et valide
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    die('ID utilisateur non spécifié');
}

$oldId = trim($_GET['id']);
if (!preg_match('/^\d{8}$/', $oldId)) {
    die("ID invalide. L'ID doit contenir exactement 8 chiffres.");
}

try {
    $utilisateurC = new UtilisateurC();
} catch (Exception $e) {
    die("Échec de l'instanciation de UtilisateurC : " . $e->getMessage());
}

try {
    $userData = $utilisateurC->getUtilisateurs($oldId);
    if (!$userData) {
        die("Utilisateur non trouvé pour l'ID $oldId.");
    }
} catch (Exception $e) {
    die("Erreur lors de la récupération des données utilisateur : " . $e->getMessage());
}

// Initialisation des variables avec les données existantes
$nom = $userData['nom'] ?? '';
$prenom = $userData['prenom'] ?? '';
$email = $userData['email'] ?? '';
$adresse = $userData['adresse'] ?? '';
$id = $userData['id'] ?? '';
$date = $userData['date'] ?? '';
$password = '';
$role = $userData['role'] ?? 'user'; // Valeur par défaut si 'role' est absent
$errors = [];
$success = false;

// Validation supplémentaire pour la date
if (!empty($date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $errors[] = "Format de date invalide : $date. Attendu : YYYY-MM-DD.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
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
        $errors[] = "❌ Tous les champs sont obligatoires.";
    }

    // Validation de l'ID : doit contenir exactement 8 chiffres
    if (!preg_match('/^\d{8}$/', $newId)) {
        $errors[] = "❌ L'ID doit contenir exactement 8 chiffres.";
    }

    // Validation du mot de passe (si fourni) : doit contenir des lettres et des chiffres
    if (!empty($password) && !preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $password)) {
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

    // Validation du rôle : doit être 'user' ou 'admin'
    if (!in_array($role, ['user', 'admin'])) {
        $errors[] = "❌ Le rôle doit être 'user' ou 'admin'.";
    }

    // Si aucune erreur, vérifier l'unicité de l'ID et de l'email
    if (empty($errors)) {
        try {
            // Vérifier si le nouvel ID est différent et déjà utilisé
            if ($newId !== $oldId) {
                $existingUser = $utilisateurC->getUtilisateurs($newId);
                if ($existingUser) {
                    $errors[] = "❌ Cet ID existe déjà. Choisissez un autre.";
                }
            }

            // Vérifier l'unicité de l'email
            $db = Databaseconfig::getConnexion();
            $stmt = $db->prepare("SELECT email FROM utilisateurs WHERE email = :email AND id != :id");
            $stmt->execute(['email' => $email, 'id' => $oldId]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "❌ Cet email est déjà utilisé. Choisissez un autre.";
            }

            // Si aucune erreur, mettre à jour l'utilisateur
            if (empty($errors)) {
                $finalPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $userData['password'];
                $updatedUser = new Utilisateur($newId, $nom, $prenom, $email, $adresse, $finalPassword, $date, $role);
                $utilisateurC->updateUtilisateurs($oldId, $updatedUser, $newId);
                $success = true;
                $_SESSION['last_updated_id'] = $newId;
                $id = $nom = $prenom = $email = $adresse = $date = $password = $role = '';
            }
        } catch (Exception $e) {
            $errors[] = "❌ Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}

// Si la mise à jour a réussi, inclure vide.php
if ($success) {
    include __DIR__ . '/vide.php';
    exit();
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
    <div >
       
    </div>

    <div class="main-content">
        <h1>Modifier Utilisateur</h1>

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
            <form method="POST" action="templateModifier.php?id=<?= htmlspecialchars($oldId) ?>">
                <label for="id">ID :</label>
                <input type="text" id="id" name="id" value="<?= htmlspecialchars($id) ?>" required>

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($adresse) ?>" required>

                <label for="password">Nouveau mot de passe (laisser vide pour ne pas modifier) :</label>
                <input type="password" id="password" name="password" placeholder="Saisir un nouveau mot de passe">

                <label for="date">Date de naissance :</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>

                <label for="role">Rôle :</label>
                <select id="role" name="role" required>
                    <option value="" <?= $role === '' ? 'selected' : '' ?>>Sélectionner un rôle</option>
                    <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>

                <input type="submit" value="Valider">
                
            </form>
           
            <a href="inscrire.php">retour</a>
        </div>
    </div>
</body>
</html>