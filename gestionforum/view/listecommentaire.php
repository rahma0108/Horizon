<?php
include_once '../controller/commentaireC.php';

$cc = new CommentaireC();
$commentaires = $cc->listeCommentaires();
?>

<h1>Liste des Commentaires</h1>
<ul>
    <?php foreach ($commentaires as $c): ?>
        <li>
            <?= htmlspecialchars($c['contenu']) ?> (<?= $c['date'] ?>)
            - post n°<?= $c['id_post'] ?>
            <a href="deletecommentaire.php?id=<?= $c['id'] ?>">Supprimer</a>
        </li>
    <?php endforeach; ?>
</ul>
