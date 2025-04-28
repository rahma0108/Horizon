<?php
include '../controller/actualiteC.php';
include '../model/actualite.php';

if (!empty($_POST)) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $image_url = $_POST['image_url'];
    $date_publication = $_POST['date_publication'];
    $id_categorie = (int)$_POST['id_categorie'];

    $actualite = new Actualite($titre, $contenu, $image_url, $date_publication, $id_categorie);
    $actualiteC = new ActualiteC();

    // Si on clique sur le bouton "Ajouter"
    if (isset($_POST['ajouter'])) {
        // Forcer la date de publication à "maintenant" (date/heure actuelle)
        $now = date('Y-m-d H:i:s');
        $actualite->setDatePublication($now);
        $actualiteC->ajouterActualite($actualite);
    }

    // Si on clique sur le bouton "Sauvegarder"
    elseif (isset($_POST['sauvegarder'])) {
        // Conserver la date choisie (même future)
        $actualiteC->ajouterActualite($actualite);
    }

    header('Location: listeActualites.php');
    exit();
}
?>
