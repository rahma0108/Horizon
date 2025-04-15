<?php
include '../controller/categorieC.php';
$categorieC = new CategorieC();
$liste = $categorieC->afficherCategories(); // ou le nom de ta méthode exacte
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Actualités</title>
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
      padding: 20px;
      background-color: #cbff39;
      min-height: 100vh;
    }

    .main h1 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: center;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tbody tr:hover {
      background-color: #eef;
    }

    td img {
      width: 80px;
      height: auto;
      border-radius: 5px;
    }

    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      color: white;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-modifier {
      background-color: #007bff;
    }

    .btn-supprimer {
      background-color: #dc3545;
    }

    .btn-ajouter {
      display: inline-block;
      background-color: #28a745;
      color: white;
      font-size: 24px;
      padding: 10px 16px;
      border-radius: 50%;
      text-decoration: none;
      position: fixed;
      bottom: 30px;
      right: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
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
  <h2>Liste des catégories</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom catégorie</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $categories = $categorieC->afficherCategories();
      if (!empty($categories)):
        foreach ($categories as $cat): ?>
          <tr>
            <td><?= htmlspecialchars($cat['id_categorie']) ?></td>
            <td><?= htmlspecialchars($cat['nom_categorie']) ?></td>
            <td>
              <a class="btn btn-modifier" href="modifierCategorie.php?id=<?= $cat['id_categorie'] ?>">Modifier</a>
              <a class="btn btn-supprimer" href="supprimerCategorie.php?id=<?= $cat['id_categorie'] ?>" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
            </td>
          </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="3">Aucune catégorie trouvée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>



  <a href="ajoutcategorie.php" class="btn-ajouter">+</a>
</div>

</body>
</html>

