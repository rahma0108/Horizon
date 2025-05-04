<?php
session_start();
header('Content-Type: application/json');

define('DIR', __DIR__ . '/../');
require_once DIR . 'vendor/autoload.php';

// Activer les logs pour déboguer
ini_set('log_errors', 1);
ini_set('error_log', DIR . 'logs/error.log');

try {
    error_log('Début de backend.php');

    // Valider le token CSRF
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['id_token'])) {
        error_log('Requête invalide : CSRF ou id_token manquant');
        echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
        exit;
    }

    error_log('Token CSRF validé');

    // Initialiser le client Google
    $client = new Google_Client(['client_id' => '82224683550-8md28f7lirmg50bj7ebj882pshvi8b6s.apps.googleusercontent.com']);
    $payload = $client->verifyIdToken($_POST['id_token']);

    if ($payload) {
        error_log('Token Google vérifié avec succès');
        $email = $payload['email'];
        $name = $payload['name'];

        // Connexion à la base de données
        require_once DIR . 'model/Databaseconfig.php';
        $dbConfig = new App\Model\Databaseconfig();
        $db = $dbConfig->getConnexion();
        error_log('Connexion à la base de données réussie');

        // Vérifier si l'utilisateur existe
        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            error_log('Utilisateur trouvé : ' . $email);
            // Mettre à jour les informations de l'utilisateur
            $stmt = $db->prepare('UPDATE utilisateurs SET nom = :nom, prenom = :prenom, name = :name WHERE email = :email');
            $stmt->execute([
                ':nom' => explode(' ', $name)[0] ?? $user['nom'],
                ':prenom' => explode(' ', $name)[1] ?? $user['prenom'] ?? '',
                ':name' => $name,
                ':email' => $email
            ]);
        } else {
            error_log('Nouvel utilisateur créé : ' . $email);
            // Créer un nouvel utilisateur
            $stmt = $db->prepare('INSERT INTO utilisateurs (nom, prenom, email, name, role) VALUES (:nom, :prenom, :email, :name, :role)');
            $stmt->execute([
                ':nom' => explode(' ', $name)[0] ?? '',
                ':prenom' => explode(' ', $name)[1] ?? '',
                ':email' => $email,
                ':name' => $name,
                ':role' => 'user'
            ]);
            // Récupérer l'utilisateur nouvellement créé
            $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Définir les variables de session avec l'ID de la base de données
        $_SESSION['authenticated'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        error_log('Session définie, réponse JSON envoyée. user_id: ' . $user['id']);
        echo json_encode(['success' => true]);
    } else {
        error_log('Token Google invalide');
        echo json_encode(['success' => false, 'message' => 'Token Google invalide.']);
    }
} catch (Exception $e) {
    error_log('Erreur Google Sign-In : ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la connexion Google : ' . $e->getMessage()]);
}
?>