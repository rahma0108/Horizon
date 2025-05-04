<?php
session_start();

// Définir le chemin vers le dossier racine
define('DIR', __DIR__ . '/../');

// Inclure l'autoloader de Composer
require_once DIR . 'vendor/autoload.php';

// Révoquer le jeton d'accès Google si présent
if (isset($_SESSION['access_token'])) {
    $client = new Google_Client();
    $client->setAccessToken($_SESSION['access_token']);
    $client->revokeToken();
}

// Détruire la session
session_unset();
session_destroy();

// Rediriger vers la page de connexion
header('Location: index.php');
exit;
?>