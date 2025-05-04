<?php

ob_start(); // prevent unwanted output
header('Content-Type: application/json');
session_start();

// Charger la bibliothèque Google API pour valider l'id_token
require_once __DIR__ . '/../vendor/autoload.php';

// Récupérer le client ID de votre projet Google
$clientId = '541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com';

// Récupérer l'id_token envoyé par le client
$input = json_decode(file_get_contents('php://input'), true);
$id_token = $input['id_token'] ?? '';

$response = ['success' => false, 'message' => ''];

if (empty($id_token)) {
    $response['message'] = 'Token manquant.';
    echo json_encode($response);
    exit();
}

try {
    // Initialiser le client Google
    $client = new Google_Client(['client_id' => $clientId]);
    $payload = $client->verifyIdToken($id_token);

    if ($payload) {
        // Vérifier que le token est valide pour votre application
        $aud = $payload['aud'];
        if ($aud !== $clientId) {
            $response['message'] = 'Token invalide pour cette application.';
            echo json_encode($response);
            exit();
        }

        // Récupérer les informations de l'utilisateur
        $email = $payload['email'];
        $google_id = $payload['sub'];

        // Vérifier si l'utilisateur existe dans votre base de données
        require_once __DIR__ . '/../model/Databaseconfig.php';
        $dbConfig = new App\Model\Databaseconfig();
        $db = $dbConfig->getConnexion();

        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Utilisateur trouvé, authentifier
            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $response['success'] = true;
        } else {
            // Si l'utilisateur n'existe pas, vous pouvez soit le créer, soit renvoyer une erreur
            $response['message'] = 'Utilisateur non trouvé. Veuillez vous inscrire.';
        }
    } else {
        $response['message'] = 'Token invalide.';
    }
} catch (Exception $e) {
    $response['message'] = 'Erreur lors de la validation du token : ' . $e->getMessage();
    error_log('Erreur Google Sign-In : ' . $e->getMessage());
}

ob_end_clean();
echo json_encode($response);
exit();


?>