<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Back Office - Gestion des Commentaires</title>
    <style>
        :root {
            --main-green: #c5ff38;
            --dark-bg: #111;
            --hover-bg: #1c1c1c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 240px;
            background-color: var(--dark-bg);
            color: white;
            padding-top: 30px;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--main-green);
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--hover-bg);
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
            width: calc(100% - 240px);
        }

        h1, h2 {
            color: var(--main-green);
        }

        .comment-box {
            background: white;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .comment {
            margin-bottom: 15px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            text-decoration: none;
            margin-right: 15px;
            color: #007bff;
            font-weight: bold;
        }

        .actions a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="forum.php">💬 Forum</a>
    <a href="gestion_commentaires.php">🛠️ Gestion Commentaires</a>
</div>

<div class="main-content">
    <h1>Back Office - Gestion des Commentaires</h1>

    <?php foreach ($commentaires as $commentaire): ?>
        <div class="comment-box">
            <div class="comment">
                <strong><?= htmlspecialchars($commentaire['auteur']) ?> :</strong>
                <p><?= htmlspecialchars($commentaire['contenu']) ?></p>
                <small><?= htmlspecialchars($commentaire['date']) ?></small>
            </div>
            <div class="actions">
                <a href="?action=delete&id=<?= $commentaire['id'] ?>" onclick="return confirm('Supprimer ce commentaire ?');">🗑 Supprimer</a>
                <a href="?action=disable&id=<?= $commentaire['id'] ?>" onclick="return confirm('Désactiver ce commentaire ?');">🚫 Désactiver</a>
            </div>
        </div>
    <?php endforeach; ?>
    <h1>Back Office - Gestion du Forum</h1>

<h2>📝 Tous les Posts</h2>
<?php foreach ($posts as $post): ?>
    <div class="comment-box">
        <strong><?= htmlspecialchars($post['auteur']) ?> :</strong>
        <p><?= htmlspecialchars($post['contenu']) ?></p>
        <small>Posté le : <?= $post['date'] ?></small>
    </div>
<?php endforeach; ?>

<h2>💬 Tous les Commentaires</h2>
<?php foreach ($commentaires as $commentaire): ?>
    <div class="comment-box">
        <strong><?= htmlspecialchars($commentaire['auteur']) ?> :</strong>
        <p><?= htmlspecialchars($commentaire['contenu']) ?></p>
        <small>Commentaire sur le post n°<?= $commentaire['id_post'] ?> - le <?= $commentaire['date'] ?></small>
    </div>
<?php endforeach; ?>

</div>

</body>
</html>
