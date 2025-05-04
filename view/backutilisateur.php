<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controller\UtilisateurC;

// Gestion des messages de succès ou d'erreur
$message = '';
if (isset($_GET['success'])) {
    $message = htmlspecialchars($_GET['success']);
} elseif (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
}

// Utiliser UtilisateurC pour récupérer les utilisateurs
$utilisateurC = new UtilisateurC();
try {
    $users = $utilisateurC->listeUtilisateurs();
} catch (Exception $e) {
    $message = "Erreur lors de la récupération des utilisateurs : " . htmlspecialchars($e->getMessage());
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs - GreenMove</title>
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

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-btn {
            background: #222;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .add-btn:hover {
            background: white;
            color: #222;
            border: 1px solid #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        thead th {
            background-color: #222;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: rgb(249, 249, 249);
        }

        tbody tr:hover {
            background-color: #efefef;
        }

        .delete-link {
            color: red;
            text-decoration: none;
        }

        .delete-link:hover {
            text-decoration: underline;
        }

        .edit-link {
            color: green;
            text-decoration: none;
        }

        .edit-link:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="backutilisateur.php">Users</a>
    <a href="reservations.php">Reservation</a>
    <a href="events.php">Events</a>
    <a href="reports.php">Reports</a>
    <a href="reclamations.php">Reclamations</a>
    <a href="shop_details.php">Shop Details</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1>Liste des Utilisateurs</h1>
        <a class="add-btn" href="front.php">+ Ajouter un Utilisateur</a>
    </div>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'Erreur') === false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <p>Aucun utilisateur enregistré.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Date de Naissance</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= isset($user['id']) ? htmlspecialchars($user['id']) : 'N/A' ?></td>
                        <td><?= isset($user['nom']) ? htmlspecialchars($user['nom']) : 'N/A' ?></td>
                        <td><?= isset($user['prenom']) ? htmlspecialchars($user['prenom']) : 'N/A' ?></td>
                        <td><?= isset($user['email']) ? htmlspecialchars($user['email']) : 'N/A' ?></td>
                        <td><?= isset($user['adresse']) ? htmlspecialchars($user['adresse']) : 'N/A' ?></td>
                        <td><?= isset($user['date']) ? htmlspecialchars($user['date']) : 'N/A' ?></td>
                        <td><?= isset($user['role']) ? htmlspecialchars($user['role']) : 'N/A' ?></td>
                        <td>
                            <a class="edit-link" href="updateutilisateur.php?id=<?= isset($user['id']) ? urlencode($user['id']) : '' ?>">Modifier</a> |
                            <a class="delete-link" href="deleteutilisateur.php?id=<?= isset($user['id']) ? urlencode($user['id']) : '' ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>