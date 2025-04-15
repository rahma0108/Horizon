<?php
include '../controller/commentaireC.php';
$commentaireC = new commentaireC();
if (isset($_GET['id'])) {
    $commentaireC->supprimerCommentaire($_GET['id']);
    header('Location: listecommentaire.php');
}
?>
