<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Vérifier si l'utilisateur est connecté et a un jeton d'accès
if (!isset($_SESSION['access_token'])) {
    header('Location: google_calendar_auth.php');
    exit;
}

// Définir le chemin vers le dossier racine
define('DIR', __DIR__ . '/../');

// Inclure l'autoloader de Composer
require_once DIR . 'vendor/autoload.php';

// Créer un client Google
$client = new Google_Client();
$client->setAccessToken($_SESSION['access_token']);

// Vérifier si le jeton est encore valide
if ($client->isAccessTokenExpired()) {
    unset($_SESSION['access_token']);
    header('Location: google_calendar_auth.php');
    exit;
}

// Créer un service Google Calendar
$service = new Google_Service_Calendar($client);

// Récupérer les événements
$calendarId = 'primary';
$events = $service->events->listEvents($calendarId, [
    'maxResults' => 10,
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => date('c'),
]);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements Google Calendar - GreenMove</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        .event {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>Vos événements Google Calendar</h2>
    <?php foreach ($events->getItems() as $event): ?>
        <div class="event">
            <strong><?= htmlspecialchars($event->getSummary()) ?></strong><br>
            Date : <?= htmlspecialchars($event->getStart()->getDateTime() ?: $event->getStart()->getDate()) ?><br>
            <a href="<?= htmlspecialchars($event->getHtmlLink()) ?>" target="_blank">Voir dans Google Calendar</a>
        </div>
    <?php endforeach; ?>
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>