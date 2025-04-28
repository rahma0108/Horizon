<?php 
include '../controller/actualiteC.php';
include '../config.php';

$actualiteC = new ActualiteC();

// Récupérer les catégories
$query = $pdo->query("SELECT id_categorie, nom_categorie FROM categorie_actualite");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $date = $_POST['date_publication'];
    $categorie = $_POST['id_categorie'];

    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $actualiteC->ajouterActualite($titre, $contenu, $image_path, $date, $categorie);
    header("Location: listeactualite.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une actualité</title>
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
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
    }

    button:hover {
      background-color: #0056b3;
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
      <input type="datetime-local" name="date_publication" required>
    </label>
    <label>Catégorie:
      <select name="id_categorie" required>
        <option value=""> Choisir une catégorie </option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['id_categorie']) ?>">
            <?= htmlspecialchars($cat['nom_categorie']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <button type="submit" name="ajouter" class="btn btn-primary">Ajouter</button>
    <button type="submit" name="sauvegarder" class="btn btn-secondary">Sauvegarder</button>
  </form>
</div>

</body>
</html>
