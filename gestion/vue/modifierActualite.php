<?php
include '../controller/actualiteC.php';
include '../config.php';

$actualiteC = new ActualiteC();

// Récupération des infos de l'actualité à modifier
$actualite = null;
if (isset($_GET['id'])) {
    $actualite = $actualiteC->getActualiteById($_GET['id']);
}

// Récupération des catégories
$query = $pdo->query("SELECT id_categorie, nom_categorie FROM categorie_actualite");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

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

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        textarea,
        select {
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
            margin-top: 20px;
        }

        button:hover {
            background-color: #218838;
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
    <form method="POST" enctype="multipart/form-data">
        <h2>Modifier l'Actualité</h2>
        <input type="hidden" name="id_actualite" value="<?= $actualite['id_actualite'] ?>">
        <input type="hidden" name="current_image" value="<?= $actualite['image_url'] ?>">

        <label>Titre:
            <input type="text" name="titre" value="<?= htmlspecialchars($actualite['titre']) ?>" required>
        </label>
        <label>Contenu:
            <textarea name="contenu" rows="5" required><?= htmlspecialchars($actualite['contenu']) ?></textarea>
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
            <input type="datetime-local" name="date_publication" value="<?= $actualite['date_publication'] ?>" required>
        </label>
        <label>Catégorie:
            <select name="id_categorie" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= ($actualite['id_categorie'] == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Modifier</button>
    </form>
</div>

</body>
</html>
