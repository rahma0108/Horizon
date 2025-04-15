<?php
include '../controller/postC.php';
include '../model/Post.php';

$postC = new postC();
if (isset($_GET['id'])) {
    $postData = $postC->recupererPost($_GET['id']);
}

if (isset($_POST['modifier'])) {
    $contenu = $_POST['contenu'];
    $date = date('Y-m-d');

    $post = new Post($contenu, $date, $postData['id_user']);
    $postC->modifierPost($post, $_GET['id']);
    header('Location: forum.php');
}
?>

<form method="POST">
    <label>Contenu :</label><br>
    <textarea name="contenu"><?= $postData['contenu']; ?></textarea><br>
    <input type="submit" name="modifier" value="Modifier">
</form>
