<?php
require_once '../../../Controller/CoursController.php';
$controller = new CoursController();

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit();
}

$id = $_GET['id'];
$cours = $controller->getCoursById($id);

if (!$cours) {
    header('Location: list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'type_cours' => $_POST['type_cours'],
        'date_cours' => $_POST['date_cours'],
        'adresse' => $_POST['adresse'],
        'prix' => $_POST['prix']
    ];

    if ($controller->updateCours($id, $data)) {
        header('Location: list.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Modifier un Cours</h2>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="type_cours" class="form-label">Type de Cours</label>
                <input type="text" class="form-control" id="type_cours" name="type_cours" 
                       value="<?= htmlspecialchars($cours['type_cours']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_cours" class="form-label">Date</label>
                <input type="date" class="form-control" id="date_cours" name="date_cours" 
                       value="<?= htmlspecialchars($cours['date_cours']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" 
                       value="<?= htmlspecialchars($cours['adresse']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" 
                       value="<?= htmlspecialchars($cours['prix']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="list.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
