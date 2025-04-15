<?php

include '../controller/actualiteC.php';

$actualiteC = new ActualiteC();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $actualiteC->supprimerActualite($id);
}

header("Location: listeactualite.php");
exit();

?>

