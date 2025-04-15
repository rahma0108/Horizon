<?php
include '../controller/categorieC.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $categorieC = new CategorieC();
    $categorieC->supprimerCategorie($_GET['id']);
}

header("Location: ajoutercategorie.php");
exit;
?>
