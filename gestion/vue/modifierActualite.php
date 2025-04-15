<?php
include '../controller/actualiteC.php';

$actualiteC = new ActualiteC();

// Récupération des infos de l'actualité à modifier
$actualite = null;
if (isset($_GET['id'])) {
    $actualite = $actualiteC->getActualiteById($_GET['id']);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id_actualite'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $date = $_POST['date_publication'];
    $categorie = $_POST['id_categorie'];
    
    $image_path = $_POST['current_image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $actualiteC->modifierActualite($id, $titre, $contenu, $image_path, $date, $categorie);
    header("Location: listeactualite.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une actualité</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 18px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 500px;
        }
        label {
            display: block;
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Modifier l'Actualité</h2>
        <input type="hidden" name="id_actualite" value="<?= $actualite['id_actualite'] ?>">
        <input type="hidden" name="current_image" value="<?= $actualite['image_url'] ?>">

        <label>Titre:
            <input type="text" name="titre" value="<?= $actualite['titre'] ?>" required>
        </label>
        <label>Contenu:
            <textarea name="contenu" rows="5" required><?= $actualite['contenu'] ?></textarea>
        </label>
        <label>Image:
            <input type="file" name="image">
            <?php if (!empty($actualite['image_url'])): ?>
                <div style="margin-top:10px;">
                    <img src="<?= $actualite['image_url'] ?>" alt="Image actuelle" width="100">
                </div>
            <?php endif; ?>
        </label>
        <label>Date de publication:
            <input type="date" name="date_publication" value="<?= $actualite['date_publication'] ?>" required>
        </label>
        <label>Catégorie ID:
            <input type="number" name="id_categorie" value="<?= $actualite['id_categorie'] ?>" required>
        </label>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
