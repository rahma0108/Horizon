<?php
include_once '../controller/commentaireC.php';
include_once '../model/Commentaire.php';
include_once '../model/ContentFilter.php';


$message = '';
if (isset($_POST['contenu'], $_POST['id_post'])) {
    
    $c = new Commentaire($_POST['contenu'], $_POST['id_post'], 1); // id_user = 1 pour test
    $cc = new CommentaireC();
    $cc->addCommentaire($c);
    header('Location: listecommentaire.php');
}

?>

<form method="post">
    <input type="hidden" name="id_post" value="<?= $_GET['id_post'] ?? 1 ?>">
    <textarea name="contenu" placeholder="Votre commentaire" required></textarea>
    <input type="submit" value="Commenter">
</form>
