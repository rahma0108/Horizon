<?php



include_once '../controller/postC.php';
include_once '../model/Post.php';
require_once '../model/ContentFilter.php';

$error = null;



if (isset($_POST['contenu'])) {
    
    $post = new Post($_POST['contenu'], 1); // id_user simulé = 1
    $postC = new PostC();
    $result=$postC->addPost($post);
    if ($result === true) {

    header('Location: listepost.php');

    } else {
        $error = $result; 
    }
}

?>



<form method="post">
    <textarea name="contenu" required placeholder="Écrivez votre post ici..."></textarea>
    <input type="submit" value="Poster">

    <?php if ($error): ?>
        <div class="error" style="color: red; margin-top: 10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

</form>

