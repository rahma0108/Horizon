<?php
include '../controller/categorieC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['nom_categorie'])) {
        $categorieC = new CategorieC();
        $categorieC->ajouterCategorie($_POST['nom_categorie']);
        header("Location: ajoutercategorie.php"); // redirection après ajout
        exit;
    } else {
        $erreur = "Le champ nom de catégorie est requis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Catégorie</title>
    <script src="form_categorie.js"></script>
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
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 450px;
        }

        label {
            display: block;
            margin-bottom: 12px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Ajouter une Catégorie</h2>
        <?php if (!empty($erreur)) echo "<div class='error'>$erreur</div>"; ?>
        <label for="nom_categorie">Nom de la catégorie :</label>
        <input type="text" name="nom_categorie" id="nom_categorie" required>
        <button type="submit">Ajouter</button>
        <a href="ajoutercategorie.php" class="back-link">Retour à la liste</a>
    </form>
</body>
</html>