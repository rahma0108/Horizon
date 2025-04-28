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
    <title>Ajouter une Catégorie</title>
    <script src="form_categorie.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            width: 220px;
            height: 100vh;
            background-color: #1e1e1e;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 0;
        }

        .main {
            margin-left: 250px;
            padding: 40px;
            background-color: #cbff39;
            min-height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 500px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 15px;
            font-weight: bold;
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
            width: 100%;
            padding: 12px;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }
        }

        .submenu {
            position: relative;
        }

        .submenu-links {
            display: none;
            margin-left: 10px;
            margin-top: 5px;
        }

        .submenu:hover .submenu-links {
            display: block;
        }

        .submenu-links a {
            font-size: 14px;
            padding: 6px 0;
            display: block;
            color: #ccc;
            text-decoration: none;
        }

        .submenu-links a:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>WELCOME</h2>
    <a href="#">Dashboard</a>
    <a href="#">Users</a>
    <a href="#">Reservation</a>
    <a href="#">Events</a>
    <a href="#">Reports</a>
    <div class="submenu">
        <a href="#">Actualité</a>
        <div class="submenu-links">
            <a href="listeactualite.php">Liste d'Actualité</a>
            <a href="ajoutercategorie.php">Catégorie</a>
        </div>
    </div>
    <a href="#">Shop Details</a>
    <a href="#">Settings</a>
    <a href="#">Logout</a>
</div>

<div class="main">
    <form method="post" action="">
        <h2>Ajouter une Catégorie</h2>
        <?php if (!empty($erreur)) echo "<div class='error'>$erreur</div>"; ?>
        <label for="nom_categorie">Nom de la catégorie :</label>
        <input type="text" name="nom_categorie" id="nom_categorie" required>
        <button type="submit">Ajouter</button>
        <a href="ajoutercategorie.php" class="back-link">Retour à la liste</a>
    </form>
</div>

</body>
</html>
