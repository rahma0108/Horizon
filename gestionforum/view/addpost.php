<?php
include_once '../controller/postC.php';
include_once '../model/Post.php';

if (isset($_POST['contenu'])) {
    $post = new Post($_POST['contenu'], 1); // id_user simulé = 1
    $postC = new PostC();
    $postC->addPost($post);
    header('Location: listepost.php');
}
?>

<form method="post">
    <textarea name="contenu" required placeholder="Écrivez votre post ici..."></textarea>
    <input type="submit" value="Poster">
</form>
