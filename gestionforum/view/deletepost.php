<?php
include '../controller/postC.php';
$postC = new postC();

if (isset($_GET['id'])) {
    $postC->supprimerPost($_GET['id']);
    header('Location: forum.php');
}
?>
