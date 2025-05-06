<?php
require_once '../../../Controller/CoursController.php';
require_once '../../../Model/Cours.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cours = new Cours();
    $cours->setType($_POST['type_cours']);
    $cours->setDate($_POST['date_cours']);
    $cours->setAdresse($_POST['adresse']);
    $cours->setPrix($_POST['prix']);

    $controller = new CoursController();
    if ($controller->addCours($cours)) {
        header('Location: list.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Cours</h2>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="type_cours" class="form-label">Type de Cours</label>
                <input type="text" class="form-control" id="type_cours" name="type_cours" required>
            </div>
            <div class="mb-3">
                <label for="date_cours" class="form-label">Date</label>
                <input type="date" class="form-control" id="date_cours" name="date_cours" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" required>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="list.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>