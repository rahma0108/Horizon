<?php
include '../controller/commentaireC.php';
include '../model/Commentaire.php';

$commentaireC = new commentaireC();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $commentaireData = $commentaireC->recupererCommentaire($id);
}

if (isset($_POST['modifier'])) {
    $contenu = $_POST['contenu'];
    $date = date('Y-m-d'); 

    $commentaire = new Commentaire($contenu, $date, $commentaireData['id_post'], $commentaireData['id_user']);
    $commentaireC->modifierCommentaire($commentaire, $id);
    header('Location: listecommentaire.php');
}
?>

<form method="POST">
    <label>Contenu</label>
    <textarea name="contenu"><?php echo $commentaireData['contenu']; ?></textarea>
    <br>
    <input type="submit" name="modifier" value="Modifier">
</form>
