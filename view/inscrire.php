<?php
// Activer la mise en mémoire tampon pour éviter les erreurs de headers
ob_start();
// Démarrer la session pour stocker l'ID et le rôle
session_start();

// Inclure l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Importer les classes avec leurs namespaces
use App\Controller\UtilisateurC;
use App\Model\Utilisateur;
use App\Model\Databaseconfig;

// Initialisation des variables pour la persistance des données
$nom = $prenom = $email = $adresse = $id = $date = $password = $role = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
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

    // Validation de l'email : doit contenir un '@'
    if (!preg_match('/@/', $email)) {
        $errors[] = "❌ L'email doit contenir un '@'.";
    }

    // Validation de la date : format YYYY-MM-DD
    if (!empty($date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "❌ La date doit être au format YYYY-MM-DD.";
    }

    // Validation du rôle : doit être 'user' ou 'admin'
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
                // Hacher le mot de passe avant de l'enregistrer
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $utilisateur = new Utilisateur($id, $nom, $prenom, $email, $adresse, $hashedPassword, $date, $role);
                $utilisateurC->addUtilisateurs($utilisateur, $id);
                // Marquer l'utilisateur comme authentifié
                $_SESSION['authenticated'] = true;
                // Stocker l'ID et le rôle dans la session
                $_SESSION['last_inserted_id'] = $id;
                $_SESSION['last_inserted_role'] = $role;
                $_SESSION['user_id'] = $id; // Pour l'affichage dans vide.php
                // Log pour déboguer
                error_log('Utilisateur ajouté avec succès. ID: ' . $id . ', Rôle: ' . $role . '. Redirection vers vide.php');
                // Rediriger vers vide.php
                header('Location: vide.php');
                exit();
            }
        } catch (Exception $e) {
            $errors[] = "❌ Erreur : " . $e->getMessage();
            error_log('Erreur lors de l\'ajout de l\'utilisateur: ' . $e->getMessage());
        }
    }
}

// ✅ Handle Google JSON Signup
define('GOOGLE_CLIENT_ID', '541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    empty($_POST) && 
    str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
    
    if (ob_get_length()) ob_clean(); // Clean any previous output
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);
    $id_token = $data['id_token'] ?? '';
    $generatedId = $data['id'] ?? '';

    $response = ['success' => false, 'message' => ''];

    if (!$id_token) {
        if (ob_get_length()) ob_clean();
        echo json_encode(['success' => false, 'message' => 'Token manquant']);
        exit;
    }

    try {
        $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
        $payload = $client->verifyIdToken($id_token);

        if ($payload && $payload['aud'] === GOOGLE_CLIENT_ID) {
            $email = $payload['email'];
            $nom = $payload['family_name'] ?? '';
            $prenom = $payload['given_name'] ?? '';
            $adresse = '';
            $date = date('Y-m-d');
            $role = 'user';
            $password = '';

            $utilisateurC = new UtilisateurC();
            $db = Databaseconfig::getConnexion();

            // Check if email already exists
            $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                if (ob_get_length()) ob_clean();
                echo json_encode(['success' => false, 'message' => 'Email déjà utilisé']);
                exit;
            }

            $utilisateur = new Utilisateur($generatedId, $nom, $prenom, $email, $adresse, $password, $date, $role);
            $utilisateurC->addUtilisateurs($utilisateur, $generatedId);

            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $generatedId;
            $_SESSION['user_role'] = $role;
            $_SESSION['name'] = $prenom . ' ' . $nom;
            if (ob_get_length()) ob_clean();

            echo json_encode(['success' => true, 'name' => $_SESSION['name']]);
            exit;
        } else {
            if (ob_get_length()) ob_clean();

            echo json_encode(['success' => false, 'message' => 'Token Google invalide']);
            exit;
        }
    } catch (Exception $e) {
        if (ob_get_length()) ob_clean();

        echo json_encode(['success' => false, 'message' => 'Erreur Google: ' . $e->getMessage()]);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Utilisateur - GreenMove</title>
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
        .form-container input[type="password"],
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
    <!-- Inclure flatpickr pour le calendrier -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#date", {
                dateFormat: "Y-m-d", // Format YYYY-MM-DD
                maxDate: "today" // Limiter à aujourd'hui
            });
        });
    </script>
</head>
<body>
    <div class="main-content">
        <h1>Inscription Utilisateur</h1>

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
            <form method="POST" action="inscrire.php">
                <label for="id">ID :</label>
                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>">

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>">

                <label for="email">Email :</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">

                <label for="date">Date de naissance :</label>
                <input type="text" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>">

                <label for="role">Rôle :</label>
                <select id="role" name="role">
                    <option value="" <?php echo $role === '' ? 'selected' : ''; ?>>Sélectionner un rôle</option>
                    <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>

                <input type="submit" value="Valider">
            </form>

            <div class="or-divider">OU</div>

            <!-- Inscription avec Google -->
            <div id="g_id_onload"
                data-client_id="541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com"
                data-context="signup"
                data-ux_mode="popup"
                data-callback="handleGoogleSignup"
                data-auto_prompt="false">
            </div>

            <div class="g_id_signin" data-type="standard"></div>

            <a href="dashboard.php">retour</a>
        </div>
    </div>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function generateRandomId() {
            return Math.floor(10000000 + Math.random() * 90000000).toString();
        }

        function handleGoogleSignup(response) {
            const id = generateRandomId();

            fetch("inscrire.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id_token: response.credential,
                    id: id
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Bienvenue " + data.name);
                    window.location.href = "vide.php";
                } else {
                    alert("Erreur: " + data.message);
                }
            })
            .catch(err => alert("Erreur lors de l'inscription: " + err.message));
        }
    </script>
</body>

</html>