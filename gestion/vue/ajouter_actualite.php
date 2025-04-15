<?php 
include '../controller/actualiteC.php';

$actualiteC = new ActualiteC();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $date = $_POST['date_publication'];
    $categorie = $_POST['id_categorie'];

    // Gérer l'upload de l'image
    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $actualiteC->ajouterActualite($titre, $contenu, $image_path, $date, $categorie);
    header("Location: listeactualite.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une actualité</title>
    <script src="form_actualite.js"></script>
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
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Ajouter une Actualité</h2>
        <label>Titre:
            <input type="text" name="titre" required>
        </label>
        <label>Contenu:
            <textarea name="contenu" rows="5" required></textarea>
        </label>
        <label>Image:
            <input type="file" name="image">
        </label>
        <label>Date de publication:
            <input type="date" name="date_publication" required>
        </label>
        <label>Catégorie ID:
            <input type="number" name="id_categorie" required>
        </label>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
