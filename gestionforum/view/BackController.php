<?php
$siteTitle = "Greenmove - Forum";

require_once '../config.php';
require_once '../controller/postC.php';
require_once '../controller/commentaireC.php';
require_once '../model/Post.php';
require_once '../model/Commentaire.php';

$postC = new PostC();
$commentaireC = new CommentaireC();

// Initialisation de variables pour gérer l'état de l'ajout de posts/commentaires
$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $_POST['type'] === 'post') {
        if (isset($_POST['update_post'])) {
            $postC->updatePost($_POST['post_id'], $_POST['contenu']);
            $success = true;
        } else {
            $post = new Post($_POST['contenu'], 1);
            $postC->addPost($post);
            $success = true;
        }
    }

    if (isset($_POST['type']) && $_POST['type'] === 'commentaire') {
        if (isset($_POST['update_commentaire'])) {
            $commentaireC->updateCommentaire($_POST['commentaire_id'], $_POST['contenu']);
            $success = true;
        } else {
            $commentaire = new Commentaire($_POST['contenu'], $_POST['id_post'], 1);
            $commentaireC->addCommentaire($commentaire);
            $success = true;
        }
    }

    // ✅ Redirection vers la même page (back-office) après action
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete_post'])) {
    $postC->deletePost($_GET['delete_post']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete_commentaire'])) {
    $commentaireC->deleteCommentaire($_GET['delete_commentaire']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$posts = $postC->listePosts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($siteTitle); ?></title>
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

        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: var(--main-green);
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="utilisateurs.php"> Utilisateurs</a>
    <a href="forum.php"> Forum</a>
    <a href="reservations.php">Réservations</a>
    <a href="magasin.php"> Magasin</a>
    <a href="actualites.php">Actualités</a>
    <a href="gestion_commentaires.php"> Gestion Commentaires</a>
</div>

<div class="main-content">
    <h1>Forum - Publier un post</h1>
    <?php if ($success): ?>
        <p style="color: green;">Action réalisée avec succès !</p>
    <?php elseif ($error): ?>
        <p style="color: red;">Une erreur est survenue, veuillez réessayer.</p>
    <?php endif; ?>

    <form method="post" action="forum.php">
        <input type="hidden" name="type" value="post">
        <textarea name="contenu" rows="4" placeholder="Écrivez ici..." required></textarea><br>
        <input class="btn" type="submit" value="Poster">
    </form>

    <h2>Posts et Commentaires</h2>
    <?php foreach ($posts as $post): ?>
        <div class="comment-box">
            <div class="comment">
                <strong>Utilisateur #<?= $post['id_user'] ?> :</strong>
                <?php if (isset($_GET['edit_post']) && $_GET['edit_post'] == $post['id']): ?>
                    <form method="post" action="forum.php">
                        <textarea name="contenu" rows="3"><?= htmlspecialchars($post['contenu']) ?></textarea>
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <input type="hidden" name="type" value="post">
                        <input class="btn" type="submit" name="update_post" value="Mettre à jour">
                    </form>
                <?php else: ?>
                    <p><?= htmlspecialchars($post['contenu']) ?></p>
                    <div class="actions">
                        <a href="?edit_post=<?= $post['id'] ?>">✏️</a>
                        <a href="?delete_post=<?= $post['id'] ?>" onclick="return confirm('Supprimer ce post ?')">🗑️</a>
                    </div>
                <?php endif; ?>

                <!-- Affichage des commentaires -->
                <?php
                $commentaires = $commentaireC->getCommentairesByPostId($post['id']);
                foreach ($commentaires as $commentaire): ?>
                    <div class="comment">
                        <strong>Utilisateur #<?= $commentaire['id_user'] ?> :</strong>
                        <p><?= htmlspecialchars($commentaire['contenu']) ?></p>
                    </div>
                <?php endforeach; ?>

                <!-- Formulaire d'ajout de commentaire -->
                <form method="post" action="forum.php">
                    <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                    <input type="hidden" name="type" value="commentaire">
                    <textarea name="contenu" rows="2" placeholder="Répondre..." required></textarea><br>
                    <input class="btn" type="submit" value="Répondre">
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
