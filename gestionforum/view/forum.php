<?php
require_once '../config.php';
require_once '../controller/postC.php';
require_once '../controller/commentaireC.php';
require_once '../model/Post.php';
require_once '../model/Commentaire.php';

$postC = new PostC();
$commentaireC = new CommentaireC();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu']) && isset($_POST['type']) && $_POST['type'] === 'post') {
    $contenu = $_POST['contenu'];
    $id_user = 1; 
    $post = new Post($contenu, $id_user);
    $postC->addPost($post);
    header("Location: forum.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu']) && isset($_POST['type']) && $_POST['type'] === 'commentaire') {
    $id_user = 1; 
    $c = new Commentaire($_POST['contenu'], $_POST['id_post'], $id_user);
    $commentaireC->addCommentaire($c);
    header("Location: forum.php");
    exit();
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
    $id = $_POST['post_id'];
    $contenu = $_POST['contenu'];
    $postC->updatePost($id, $contenu);
    header("Location: forum.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_commentaire'])) {
    $id = $_POST['commentaire_id'];
    $contenu = $_POST['contenu'];
    $commentaireC->updateCommentaire($id, $contenu);
    header("Location: forum.php");
    exit();
}


$posts = $postC->listePosts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Forum - Gestion des commentaires</title>
    <style>
        :root {
            --main-green: #c5ff38;
            --dark-bg: #111;
            --hover-bg: #1c1c1c;
        }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; background-color: #f8f9fa; }
        .sidebar { width: 240px; background-color: var(--dark-bg); color: white; padding-top: 30px; height: 100vh; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; color: var(--main-green); }
        .sidebar a { display: block; color: white; padding: 15px 25px; text-decoration: none; font-size: 16px; transition: background 0.3s; }
        .sidebar a:hover { background-color: var(--hover-bg); }
        .main-content { margin-left: 240px; padding: 30px; width: calc(100% - 240px); }
        h1, h2 { color: var(--main-green); }
        .comment-box { background: white; margin-bottom: 30px; border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .comment { margin-bottom: 15px; padding: 10px; background: #f0f0f0; border-radius: 5px; position: relative; }
        .reponse { margin-left: 25px; padding: 8px; background-color: #f4ffe6; border-left: 3px solid var(--main-green); border-radius: 4px; margin-top: 10px; position: relative; }
        .action-buttons { position: absolute; top: 5px; right: 10px; font-size: 13px; }
        .action-buttons a, .action-buttons form { display: inline; margin-left: 10px; }
        .action-buttons form { display: inline; }
        .action-buttons button { background: none; border: none; color: #d00; cursor: pointer; }
        textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; resize: vertical; }
        input[type="submit"] { background-color: var(--main-green); color: black; border: none; padding: 10px 20px; margin-top: 10px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        input[type="submit"]:hover { background-color: #b2e936; }
        hr { border: none; height: 1px; background-color: #ccc; margin: 40px 0; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Navigation</h2>
    <a href="#">🏪 Magasin</a>
    <a href="#">📆 Réservations</a>
    <a href="#">💬 Forum</a>
    <a href="#">👥 Clients</a>
</div>

<div class="main-content">
    <h1>Forum - Publier un post</h1>
    <div class="comment-box">
        <form method="post">
            <textarea name="contenu" rows="4" placeholder="Écrivez votre post ici..." required></textarea><br>
            <input type="hidden" name="type" value="post">
            <input type="submit" value="Poster">
        </form>
    </div>

    <hr>

    <h2>Posts et Commentaires</h2>

    <?php foreach ($posts as $post): ?>
        <div class="comment-box">
            <div class="comment">
                <strong>Utilisateur #<?= $post['id_user'] ?> :</strong><br>
                <?php if (isset($_GET['edit_post']) && $_GET['edit_post'] == $post['id']): ?>
                    <form method="post">
                        <textarea name="contenu" rows="3"><?= htmlspecialchars($post['contenu']) ?></textarea>
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <input type="submit" name="update_post" value="Mettre à jour">
                    </form>
                <?php else: ?>
                    <p><?= htmlspecialchars($post['contenu']) ?></p>
                    <em><?= $post['date'] ?></em>
                    <div class="action-buttons">
                        <a href="?edit_post=<?= $post['id'] ?>">✏️</a>
                        <a href="?delete_post=<?= $post['id'] ?>" onclick="return confirm('Supprimer ce post ?')">🗑️</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php
            $commentaires = $commentaireC->getCommentairesByPostId($post['id']);
            foreach ($commentaires as $commentaire):
            ?>
                <div class="reponse">
                    <strong>Utilisateur #<?= $commentaire['id_user'] ?> :</strong><br>
                    <?php if (isset($_GET['edit_commentaire']) && $_GET['edit_commentaire'] == $commentaire['id']): ?>
                        <form method="post">
                            <textarea name="contenu" rows="2"><?= htmlspecialchars($commentaire['contenu']) ?></textarea>
                            <input type="hidden" name="commentaire_id" value="<?= $commentaire['id'] ?>">
                            <input type="submit" name="update_commentaire" value="Mettre à jour">
                        </form>
                    <?php else: ?>
                        <?= htmlspecialchars($commentaire['contenu']) ?><br>
                        <small><?= $commentaire['date'] ?></small>
                        <div class="action-buttons">
                            <a href="?edit_commentaire=<?= $commentaire['id'] ?>">✏️</a>
                            <a href="?delete_commentaire=<?= $commentaire['id'] ?>" onclick="return confirm('Supprimer ce commentaire ?')">🗑️</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <form method="post">
                <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                <input type="hidden" name="type" value="commentaire">
                <textarea name="contenu" rows="2" placeholder="Répondre..." required></textarea><br>
                <input type="submit" value="Répondre">
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
