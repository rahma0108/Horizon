<?php
include_once '../controller/postC.php';

$postC = new PostC();
$liste = $postC->listePost();
?>

<h1>Liste des Posts</h1>
<ul>
    <?php foreach ($liste as $post): ?>
        <li>
            <?= htmlspecialchars($post['contenu']) ?> (<?= $post['date'] ?>)
            <a href="deletepost.php?id=<?= $post['id'] ?>">Supprimer</a>
        </li>
    <?php endforeach; ?>
    
</ul>
