<?php
include '../controller/actualitesC.php';
include '../model/actualites.php';

if (!empty($_POST)) {
    $actualite = new Actualite(
        $_POST['titre'],
        $_POST['contenu'],
        $_POST['image_url'],
        $_POST['date_publication'],
        (int)$_POST['id_categorie']
    );

    (new ActualiteC())->ajouterActualite($actualite);
    header('Location: listeActualites.php');
    exit();
}
?>
