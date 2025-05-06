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
    if (isset($_POST['type'])) {
        // Traitement des posts
        if ($_POST['type'] === 'post') {
            if (isset($_POST['update_post'])) {
                $postC->updatePost($_POST['post_id'], $_POST['contenu']);
                $success = true;
            } else {
                $post = new Post($_POST['contenu'], 1);
                $result = $postC->addPost($post);
                if ($result === true) {
                    $success = true;
                } else {
                    $error = $result;
                }
            }
        }

        // Traitement des commentaires
        if ($_POST['type'] === 'commentaire') {
            if (isset($_POST['update_commentaire'])) {
                $commentaireC->updateCommentaire($_POST['commentaire_id'], $_POST['contenu']);
                $success = true;
            } else {
                $commentaire = new Commentaire($_POST['contenu'], $_POST['id_post'], 1);
                $commentaireC->addCommentaire($commentaire);
                $success = true;
            }
        }

        // Traitement des signalements
        if (isset($_POST['report_submit'])) {
            try {
                $report_type = $_POST['report_type'];
                $content_id = $_POST['content_id'];
                $raison = $_POST['raison'];
                $details = $_POST['details'] ?? '';
                $user_id = 1; // id_user simulé

                if ($report_type === 'post') {
                    $postC->signalerPost($content_id, $user_id, $raison, $details);
                } elseif ($report_type === 'comment') {
                    $commentaireC->signalerCommentaire($content_id, $user_id, $raison, $details);
                }
                $success = true;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        // Redirection vers la même page après action
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .comment-table {
            margin: 10px 0 10px 20px;
            background: #f8f9fa;
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .actions a {
            text-decoration: none;
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
            border-radius: 5px;
        }

        .btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .btn-flag {
            background: none;
            border: none;
            color: #ff6b6b;
            cursor: pointer;
            font-size: 1.2em;
        }

        .flag-count {
            color: #ff6b6b;
            font-size: 0.8em;
            margin-left: 2px;
        }

        button {
            font-size: 16px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            color: #333;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="utilisateurs.php">Utilisateurs</a>
    <a href="forum.php">Forum</a>
    <a href="reservations.php">Réservations</a>
    <a href="magasin.php">Magasin</a>
    <a href="actualites.php">Actualités</a>
    <a href="gestion_commentaires.php">Gestion Commentaires</a>
</div>

<div class="main-content">
    <h1>Forum - Publier un post</h1>
    <?php if ($success): ?>
        <p style="color: green;">Action réalisée avec succès !</p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="forum.php">
        <input type="hidden" name="type" value="post">
        <textarea name="contenu" rows="4" placeholder="Écrivez ici..." required></textarea><br>
        <input class="btn" type="submit" value="Poster">
    </form>

    <h2>Posts et Commentaires</h2>
    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Contenu</th>
                <th>Date</th>
                <th>Likes</th>
                <th>Dislikes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <?php
                $reactions = Post::getReactions($post['id']);
                $likes = 0;
                $dislikes = 0;
                foreach ($reactions as $r) {
                    if ($r['reaction'] === 'like') $likes = $r['count'];
                    if ($r['reaction'] === 'dislike') $dislikes = $r['count'];
                }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($post['id_user']); ?></td>
                    <td>
                        <?php if (isset($_GET['edit_post']) && $_GET['edit_post'] == $post['id']): ?>
                            <form method="post" action="forum.php">
                                <textarea name="contenu" rows="3"><?php echo htmlspecialchars($post['contenu']); ?></textarea>
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="hidden" name="type" value="post">
                                <input class="btn" type="submit" name="update_post" value="Mettre à jour">
                            </form>
                        <?php else: ?>
                            <?php echo htmlspecialchars($post['contenu']); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($post['date']); ?></td>
                    <td><?php echo $likes; ?></td>
                    <td><?php echo $dislikes; ?></td>
                    <td class="actions">
                        <?php if (!isset($_GET['edit_post']) || $_GET['edit_post'] != $post['id']): ?>
                            <a href="?edit_post=<?php echo $post['id']; ?>">✏️</a>
                            <a href="?delete_post=<?php echo $post['id']; ?>" onclick="return confirm('Supprimer ce post ?')">🗑️</a>
                            <button class="btn-flag" onclick="showReportModal('post', <?php echo $post['id']; ?>)">🚩</button>
                            
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <!-- Tableaux des commentaires -->
                        <?php
                        $commentaires = $commentaireC->getCommentairesByPostId($post['id']);
                        if (!empty($commentaires)): ?>
                            <table class="comment-table">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Contenu</th>
                                        <th>Date</th>
                                        <th>Likes</th>
                                        <th>Dislikes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($commentaires as $commentaire): ?>
                                        <?php
                                        $reactionsC = Commentaire::getReactions($commentaire['id']);
                                        $likesC = 0;
                                        $dislikesC = 0;
                                        foreach ($reactionsC as $rC) {
                                            if ($rC['reaction'] === 'like') $likesC = $rC['count'];
                                            if ($rC['reaction'] === 'dislike') $dislikesC = $rC['count'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($commentaire['id_user']); ?></td>
                                            <td>
                                                <?php if (isset($_GET['edit_commentaire']) && $_GET['edit_commentaire'] == $commentaire['id']): ?>
                                                    <form method="post" action="forum.php">
                                                        <textarea name="contenu" rows="2"><?php echo htmlspecialchars($commentaire['contenu']); ?></textarea>
                                                        <input type="hidden" name="commentaire_id" value="<?php echo $commentaire['id']; ?>">
                                                        <input type="hidden" name="type" value="commentaire">
                                                        <input class="btn" type="submit" name="update_commentaire" value="Mettre à jour">
                                                    </form>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($commentaire['contenu']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($commentaire['date']); ?></td>
                                            <td><?php echo $likesC; ?></td>
                                            <td><?php echo $dislikesC; ?></td>
                                            <td class="actions">
                                                <?php if (!isset($_GET['edit_commentaire']) || $_GET['edit_commentaire'] != $commentaire['id']): ?>
                                                    <a href="?edit_commentaire=<?php echo $commentaire['id']; ?>">✏️</a>
                                                    <a href="?delete_commentaire=<?php echo $commentaire['id']; ?>" onclick="return confirm('Supprimer ce commentaire ?')">🗑️</a>
                                                    <button class="btn-flag" onclick="showReportModal('comment', <?php echo $commentaire['id']; ?>)">🚩</button>
                                                   
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <!-- Formulaire d'ajout de commentaire -->
                        <form method="post" action="forum.php">
                            <input type="hidden" name="id_post" value="<?php echo $post['id']; ?>">
                            <input type="hidden" name="type" value="commentaire">
                            <textarea name="contenu" rows="2" placeholder="Répondre..." required></textarea><br>
                            <input class="btn" type="submit" value="Répondre">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal de signalement -->
<div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">×</span>
        <h3>Signaler ce contenu</h3>
        <form method="post">
            <input type="hidden" name="report_type" id="reportType">
            <input type="hidden" name="content_id" id="contentId">
            <input type="hidden" name="report_submit" value="1">
            
            <div class="form-group">
                <label>Raison :</label>
                <select name="raison" required>
                    <option value="spam">Spam</option>
                    <option value="inapproprié">Contenu inapproprié</option>
                    <option value="harcèlement">Harcèlement</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Détails (optionnel) :</label>
                <textarea name="details" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn">Envoyer le signalement</button>
        </form>
    </div>
</div>

<script>
function showReportModal(type, id) {
    document.getElementById('reportType').value = type;
    document.getElementById('contentId').value = id;
    document.getElementById('reportModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('reportModal').style.display = 'none';
}

// Fermer quand on clique en dehors
window.onclick = function(event) {
    if (event.target === document.getElementById('reportModal')) {
        closeModal();
    }
}
</script>

</body>
</html>