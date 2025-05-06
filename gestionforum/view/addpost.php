<?php
session_start();
require_once '../controller/postC.php';
require_once '../model/Post.php';
require_once '../model/ContentFilter.php';
require_once '../config.php';

// Initialisation
$error = '';
$success = '';
$postC = new PostC();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu'])) {
    $content = trim($_POST['contenu']);
    
    try {
        // Validation
        if (empty($content)) {
            throw new Exception("Le contenu ne peut pas être vide");
        }
        
        if (ContentFilter::containsBadWords($content)) {
            throw new Exception("Votre message contient des termes inappropriés");
        }
        
        // Création du post
        $post = new Post($content, $_SESSION['user_id'] ?? 1); // Utilise l'ID de session ou 1 par défaut
        $result = $postC->addPost($post);
        
        if ($result) {
            $success = "Votre post a été publié avec succès!";
            $_POST['contenu'] = ''; // Vide le champ après succès
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupération des posts
$posts = $postC->listePosts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Communautaire</title>
    <style>
        :root {
            --primary-color: #4285f4;
            --error-color: #d9534f;
            --success-color: #5cb85c;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .post-form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .post-form h2 {
            margin-top: 0;
            color: var(--primary-color);
        }
        
        textarea {
            width: 100%;
            min-height: 120px;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 16px;
            resize: vertical;
            transition: border 0.3s;
        }
        
        textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.2);
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        button:hover {
            background-color: #3367d6;
        }
        
        .message {
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: var(--error-color);
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background-color: #d4edda;
            color: var(--success-color);
            border: 1px solid #c3e6cb;
        }
        
        .posts-container {
            display: grid;
            gap: 20px;
        }
        
        .post {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .post-content {
            white-space: pre-wrap;
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .post-meta {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 14px;
            border-top: 1px solid var(--light-gray);
            padding-top: 10px;
        }
        
        .post-actions {
            margin-top: 10px;
        }
        
        .post-actions a {
            color: var(--primary-color);
            text-decoration: none;
            margin-right: 15px;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Forum Communautaire</h1>
        <p>Partagez vos idées et discutez avec la communauté</p>
    </header>

    <div class="container">
        <section class="post-form">
            <h2>Créer un nouveau post</h2>
            
            <?php if (!empty($error)): ?>
                <div class="message error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="message success-message"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="post">
                <textarea name="contenu" placeholder="Quoi de neuf ?"><?= isset($_POST['contenu']) ? htmlspecialchars($_POST['contenu']) : '' ?></textarea>
                <br>
                <button type="submit">Publier</button>
            </form>
        </section>

        <section class="posts-container">
            <h2>Derniers posts</h2>
            
            <?php if (empty($posts)): ?>
                <p>Aucun post disponible. Soyez le premier à poster !</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <div class="post-content">
                            <?= nl2br(htmlspecialchars($post['contenu'])) ?>
                        </div>
                        <div class="post-meta">
                            <span>Posté par <strong><?= htmlspecialchars($post['auteur']) ?></strong></span>
                            <span><?= date('d/m/Y à H:i', strtotime($post['date'])) ?></span>
                        </div>
                        <div class="post-actions">
                            <a href="#">Répondre</a>
                            <a href="#">Partager</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>