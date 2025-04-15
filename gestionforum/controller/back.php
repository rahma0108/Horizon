<?php
require_once '../model/commentaire.php';
require_once '../config.php';
require_once '../model/post.php';


$posts = Post::getAllPosts() ?? [];
$commentaires = Commentaire::getAllCommentaires() ?? [];

include('../view/BackController.php');

if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($_GET['action'] === 'delete') {
        Commentaire::supprimer($id);
    } elseif ($_GET['action'] === 'disable') {
        Commentaire::desactiver($id);
    }
}




