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

<form method="post" onsubmit="return validateCommentForm();">
    <textarea name="contenu" id="commentContenu" placeholder="Écrivez votre commentaire ici..."></textarea>
    <div id="commentError" style="color: red; margin-top: 5px;"></div>
    <input type="submit" value="Commenter">
</form>

<script>
function validateCommentForm() {
    const contenu = document.getElementById('commentContenu').value.trim();
    const errorDiv = document.getElementById('commentError');

    if (contenu === "") {
        errorDiv.textContent = "Erreur : le contenu du commentaire ne peut pas être vide.";
        return false;
    }

    errorDiv.textContent = "";
    return true;
}
</script>
