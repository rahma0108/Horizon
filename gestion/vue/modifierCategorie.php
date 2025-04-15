<?php
include '../controller/categorieC.php';

$categorieC = new CategorieC();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $categorie = $categorieC->recupererCategorie($id);

    if (!$categorie) {
        die("Catégorie introuvable.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_categorie'])) {
        $nom = trim($_POST['nom_categorie']);
        if (!empty($nom)) {
            $categorieC->modifierCategorie($id, $nom);
            header("Location: ajoutercategorie.php");
            exit;
        } else {
            $erreur = "Le nom est requis.";
        }
    }
} else {
    die("ID invalide.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une catégorie</title>
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
            width: 400px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #ffc107;
            color: #000;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e0a800;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Modifier la Catégorie</h2>
        <?php if (!empty($erreur)) echo "<div class='error'>$erreur</div>"; ?>
        <label>Nom de la catégorie :</label>
        <input type="text" name="nom_categorie" value="<?= htmlspecialchars($categorie['nom_categorie']) ?>" required>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
