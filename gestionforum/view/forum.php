<?php
session_start();
$siteTitle = "Fitsense - Club Multisports";

require_once '../config.php';
require_once '../controller/postC.php';
require_once '../controller/commentaireC.php';
require_once '../model/Post.php';
require_once '../model/Commentaire.php';
require_once '../model/ContentFilter.php';

// Initialisation
$error = '';
$success = '';
$postC = new PostC();
$commentaireC = new CommentaireC();
$savedContent = '';

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
        // Traitement des signalements
        if (isset($_POST['report_submit'])) {
            try {
                $report_type = $_POST['report_type'];
                $content_id = $_POST['content_id'];
                $raison = $_POST['raison'];
                $details = $_POST['details'] ?? '';
                $user_id = $_SESSION['user_id'] ?? 1;

                if ($report_type === 'post') {
                    $postC->signalerPost($content_id, $user_id, $raison, $details);
                } elseif ($report_type === 'comment') {
                    $commentaireC->signalerCommentaire($content_id, $user_id, $raison, $details);
                }

                $_SESSION['success'] = "Signalement envoyé avec succès";
                header("Location: forum.php");
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Traitement des posts
        if ($_POST['type'] === 'post') {
            $savedContent = $_POST['contenu'];
            $content = trim($savedContent);
            
            try {
                if (empty($content)) {
                    throw new Exception("Le contenu ne peut pas être vide");
                }
                
                if (ContentFilter::containsBadWords($content)) {
                    throw new Exception("Votre message contient des termes inappropriés");
                }
                
                if (isset($_POST['update_post'])) {
                    $postC->updatePost($_POST['post_id'], $content);
                    $success = "Post mis à jour avec succès!";
                } else {
                    $post = new Post($content, $_SESSION['user_id'] ?? 1);
                    $postC->addPost($post);
                    $success = "Post publié avec succès!";
                    $savedContent = '';
                }
                header("Location: forum.php?success=1");
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        // Traitement des commentaires
        if ($_POST['type'] === 'commentaire') {
            try {
                $content = trim($_POST['contenu']);
                if (empty($content)) {
                    throw new Exception("Le commentaire ne peut pas être vide");
                }
                
                if (ContentFilter::containsBadWords($content)) {
                    throw new Exception("Votre commentaire contient des termes inappropriés");
                }
                
                if (isset($_POST['update_commentaire'])) {
                    $commentaireC->updateCommentaire($_POST['commentaire_id'], $content);
                    $success = "Commentaire mis à jour avec succès!";
                } else {
                    $commentaire = new Commentaire($content, $_POST['id_post'], $_SESSION['user_id'] ?? 1);
                    $commentaireC->addCommentaire($commentaire);
                    $success = "Commentaire ajouté avec succès!";
                }
                header("Location: forum.php?success=1");
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        if ($_POST['type'] === 'reaction_post') {
            $postC->reactToPost($_SESSION['user_id'] ?? 1, $_POST['id_post'], $_POST['reaction']);
            header("Location: forum.php");
            exit();
        }

        if ($_POST['type'] === 'reaction_commentaire') {
            $commentaireC->reactToCommentaire($_SESSION['user_id'] ?? 1, $_POST['id_commentaire'], $_POST['reaction']);
            header("Location: forum.php");
            exit();
        }
    }
}

// Gestion des suppressions
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

// Gestion du message de succès après redirection
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Opération effectuée avec succès!";
}

// Récupération des posts
$posts = $postC->listePosts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteTitle) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        /* Styles pour les messages */
        .message-container {
            margin: 15px 0;
        }
        
        .error-message {
            color: #ff6b6b;
            background: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        
        .success-message {
            color: #51cf66;
            background: rgba(0, 255, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        
        .form-group {
            margin-bottom: 15px;
        }

        /* Styles pour les signalements */
        .btn-flag {
            background: none;
            border: none;
            color: #ff6b6b;
            cursor: pointer;
            font-size: 1.2em;
            margin-left: 10px;
        }

        

        /* Modal de signalement */
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
    <!-- Messages globaux -->
    <div class="message-container">
        <?php if (!empty($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>

    <h1>Publier un post</h1>
    <form method="post" action="forum.php">
        <input type="hidden" name="type" value="post">
        <div class="form-group">
            <textarea name="contenu" rows="4" placeholder="Écrivez ici..."><?= isset($_POST['contenu']) ? htmlspecialchars($_POST['contenu']) : '' ?></textarea>
            <?php if (!empty($error) && isset($_POST['type']) && $_POST['type'] === 'post'): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </div>
        <input class="btn" type="submit" value="Poster">
    </form>

    <div class="btn-group"></div>

    <h2>Posts et Commentaires</h2>

    <?php foreach ($posts as $post): ?>
        <div class="post">
            <p><strong>Utilisateur #<?= $post['id_user'] ?> :</strong></p>
            <?php if (isset($_GET['edit_post']) && $_GET['edit_post'] == $post['id']): ?>
                <form method="post" action="forum.php">
                    <div class="form-group">
                        <textarea name="contenu" rows="3"><?= htmlspecialchars($post['contenu']) ?></textarea>
                        <?php if (!empty($error) && isset($_POST['type']) && $_POST['type'] === 'post' && isset($_POST['update_post'])): ?>
                            <div class="error-message"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="type" value="post">
                    <input class="btn" type="submit" name="update_post" value="Mettre à jour">
                </form>
            <?php else: ?>
                <p><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
                <div class="actions">
                    <a href="?edit_post=<?= $post['id'] ?>">✏️</a>
                    <a href="?delete_post=<?= $post['id'] ?>" onclick="return confirm('Supprimer ce post ?')">🗑️</a>
                    <button class="btn-flag" onclick="showReportModal('post', <?= $post['id'] ?>)">🚩</button>
                    
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
                            <div class="form-group">
                                <textarea name="contenu" rows="2"><?= htmlspecialchars($commentaire['contenu']) ?></textarea>
                                <?php if (!empty($error) && isset($_POST['type']) && $_POST['type'] === 'commentaire' && isset($_POST['update_commentaire'])): ?>
                                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="commentaire_id" value="<?= $commentaire['id'] ?>">
                            <input type="hidden" name="type" value="commentaire">
                            <input class="btn" type="submit" name="update_commentaire" value="Mettre à jour">
                        </form>
                    <?php else: ?>
                        <p><?= nl2br(htmlspecialchars($commentaire['contenu'])) ?></p>
                        <div class="actions">
                            <a href="?edit_commentaire=<?= $commentaire['id'] ?>">✏️</a>
                            <a href="?delete_commentaire=<?= $commentaire['id'] ?>" onclick="return confirm('Supprimer ce commentaire ?')">🗑️</a>
                            <button class="btn-flag" onclick="showReportModal('comment', <?= $commentaire['id'] ?>)">🚩</button>
                            
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
                <div class="form-group">
                    <textarea name="contenu" rows="2" placeholder="Écrire un commentaire..."></textarea>
                    <?php if (!empty($error) && isset($_POST['type']) && $_POST['type'] === 'commentaire' && isset($_POST['id_post']) && $_POST['id_post'] == $post['id']): ?>
                        <div class="error-message"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                </div>
                <input class="btn" type="submit" value="Commenter">
            </form>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal de signalement -->
<div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
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