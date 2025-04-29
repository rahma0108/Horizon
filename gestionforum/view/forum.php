<?php
$siteTitle = "Fitsense - Club Multisports";

require_once '../config.php';
require_once '../controller/postC.php';
require_once '../controller/commentaireC.php';
require_once '../model/Post.php';
require_once '../model/Commentaire.php';

$postC = new PostC();
$commentaireC = new CommentaireC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $_POST['type'] === 'post') {
        if (isset($_POST['update_post'])) {
            $postC->updatePost($_POST['post_id'], $_POST['contenu']);
        } else {
            $post = new Post($_POST['contenu'], 1); // id_user = 1
            $postC->addPost($post);
        }
        header("Location: forum.php");
        exit();
    }

    if (isset($_POST['type']) && $_POST['type'] === 'commentaire') {
        if (isset($_POST['update_commentaire'])) {
            $commentaireC->updateCommentaire($_POST['commentaire_id'], $_POST['contenu']);
        } else {
            $commentaire = new Commentaire($_POST['contenu'], $_POST['id_post'], 1); // id_user = 1
            $commentaireC->addCommentaire($commentaire);
        }
        header("Location: forum.php");
        exit();
    }

    if ($_POST['type'] === 'reaction_post') {
        $postC->reactToPost(1, $_POST['id_post'], $_POST['reaction']);
        header("Location: forum.php");
        exit();
    }

    if ($_POST['type'] === 'reaction_commentaire') {
        $commentaireC->reactToCommentaire(1, $_POST['id_commentaire'], $_POST['reaction']);
        header("Location: forum.php");
        exit();
    }
}

if (isset($_GET['delete_post'])) {
    $postC->deletePost($_GET['delete_post']);
    header("Location: forum.php");
    exit();
}

if (isset($_GET['delete_commentaire'])) {
    $commentaireC->deleteCommentaire($_GET['delete_commentaire']);
    header("Location: forum.php");
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
        body {
            font-family: Arial, sans-serif;
            padding-top: 80px;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar .menu a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }

        .navbar .menu a:hover {
            color: #c5ff38;
        }

        .container {
            width: 80%;
            margin: auto;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
        }

        h1, h2 {
            color: #c5ff38;
        }

        textarea {
            width: 100%;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            background-color: #c5ff38;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .post, .comment {
            background: rgba(255, 255, 255, 0.1);
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
        }

        .reponse {
            margin-left: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-left: 3px solid #c5ff38;
            border-radius: 5px;
        }

        .actions a {
            margin-left: 10px;
            color: #ff7b7b;
            text-decoration: none;
            font-weight: bold;
        }

        button {
            font-size: 16px;
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
        }

        .btn-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>





<div class="navbar">
    <div class="logo">Greenmove</div>
    <div class="menu">
        <a href="#">Accueil</a>
        <a href="#">Activités</a>
        <a href="#">Événements</a>
        <a href="#">forum</a>
        <a href="#">magasin</a>
    </div>
</div>

<div class="container">
    <h1>Publier un post</h1>
    <form method="post" action="forum.php">
        <input type="hidden" name="type" value="post">
        <textarea name="contenu" rows="4" placeholder="Écrivez ici..." required></textarea><br>
        <input class="btn" type="submit" value="Poster">
    </form>

    <div class="btn-group"></div>

    <h2>Posts et Commentaires</h2>

    <?php foreach ($posts as $post): ?>
        <div class="post">
            <p><strong>Utilisateur #<?= $post['id_user'] ?> :</strong></p>
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

            <?php
            $reactions = Post::getReactions($post['id']);
            $likes = 0;
            $dislikes = 0;
            foreach ($reactions as $r) {
                if ($r['reaction'] === 'like') $likes = $r['count'];
                if ($r['reaction'] === 'dislike') $dislikes = $r['count'];
            }
            ?>
            <div class="actions">
                <form method="post" action="forum.php" style="display:inline;">
                    <input type="hidden" name="type" value="reaction_post">
                    <input type="hidden" name="reaction" value="like">
                    <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                    <button type="submit">👍 <?= $likes ?></button>
                </form>
                <form method="post" action="forum.php" style="display:inline;">
                    <input type="hidden" name="type" value="reaction_post">
                    <input type="hidden" name="reaction" value="dislike">
                    <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                    <button type="submit">👎 <?= $dislikes ?></button>
                </form>
            </div>

            <?php
            $commentaires = $commentaireC->getCommentairesByPostId($post['id']);
            foreach ($commentaires as $commentaire): ?>
                <div class="reponse">
                    <p><strong>Utilisateur #<?= $commentaire['id_user'] ?> :</strong></p>
                    <?php if (isset($_GET['edit_commentaire']) && $_GET['edit_commentaire'] == $commentaire['id']): ?>
                        <form method="post" action="forum.php">
                            <textarea name="contenu" rows="2"><?= htmlspecialchars($commentaire['contenu']) ?></textarea>
                            <input type="hidden" name="commentaire_id" value="<?= $commentaire['id'] ?>">
                            <input type="hidden" name="type" value="commentaire">
                            <input class="btn" type="submit" name="update_commentaire" value="Mettre à jour">
                        </form>
                    <?php else: ?>
                        <p><?= htmlspecialchars($commentaire['contenu']) ?></p>
                        <div class="actions">
                            <a href="?edit_commentaire=<?= $commentaire['id'] ?>">✏️</a>
                            <a href="?delete_commentaire=<?= $commentaire['id'] ?>" onclick="return confirm('Supprimer ce commentaire ?')">🗑️</a>
                        </div>
                    <?php endif; ?>

                    <?php
                    $reactionsC = Commentaire::getReactions($commentaire['id']);
                    $likesC = 0;
                    $dislikesC = 0;
                    foreach ($reactionsC as $rC) {
                        if ($rC['reaction'] === 'like') $likesC = $rC['count'];
                        if ($rC['reaction'] === 'dislike') $dislikesC = $rC['count'];
                    }
                    ?>
                    <div class="actions">
                        <form method="post" action="forum.php" style="display:inline;">
                            <input type="hidden" name="type" value="reaction_commentaire">
                            <input type="hidden" name="reaction" value="like">
                            <input type="hidden" name="id_commentaire" value="<?= $commentaire['id'] ?>">
                            <button type="submit">👍 <?= $likesC ?></button>
                        </form>
                        <form method="post" action="forum.php" style="display:inline;">
                            <input type="hidden" name="type" value="reaction_commentaire">
                            <input type="hidden" name="reaction" value="dislike">
                            <input type="hidden" name="id_commentaire" value="<?= $commentaire['id'] ?>">
                            <button type="submit">👎 <?= $dislikesC ?></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <form method="post" action="forum.php">
                <input type="hidden" name="type" value="commentaire">
                <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                <textarea name="contenu" rows="2" placeholder="Écrire un commentaire..." required></textarea>
                <input class="btn" type="submit" value="Commenter">
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
