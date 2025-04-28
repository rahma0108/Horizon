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
    <title>Modifier une Catégorie</title>
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
            margin-bottom: 10px;
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
        <h2>Modifier la Catégorie</h2>
        <?php if (!empty($erreur)) echo "<div class='error'>$erreur</div>"; ?>
        <label>Nom de la catégorie :</label>
        <input type="text" name="nom_categorie" value="<?= htmlspecialchars($categorie['nom_categorie']) ?>" required>
        <button type="submit">Modifier</button>
        <a href="ajoutercategorie.php" class="back-link">Retour à la liste</a>
    </form>
</div>

</body>
</html>
