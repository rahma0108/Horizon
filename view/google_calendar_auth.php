<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location:vide.php');
    exit;
}

// Définir le chemin vers le dossier racine
define('DIR', __DIR__ . '/../');

// Inclure l'autoloader de Composer
require_once DIR . 'vendor/autoload.php';

// Charger les variables d'environnement depuis env.php
$env = require_once DIR . 'env.php';

// Créer un client Google
$client = new Google_Client();
$client->setClientId('82224683550-8md28f7lirmg50bj7ebj882pshvi8b6s.apps.googleusercontent.com');
$client->setClientSecret($env['GOOGLE_CLIENT_SECRET']); 
$client->setRedirectUri('http://localhost:8081/projet/view/vide.php');
$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

// Gérer le callback après l'autorisation
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) 
    {$_SESSION['access_token'] = $token;

        header('Location: calendar.php');
        exit;
    } else {
        echo "Erreur lors de la récupération du token : " . $token['error'];
    }
} else {
    // Générer l'URL d'authentification
    $authUrl = $client->createAuthUrl();
    echo "<h2>Connexion à Google Calendar</h2>";
    echo "<a href='" . htmlspecialchars($authUrl) . "'>Se connecter avec Google pour accéder au calendrier</a>";
}
?>