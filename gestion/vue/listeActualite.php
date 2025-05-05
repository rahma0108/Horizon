<?php 
include '../controller/actualiteC.php';
$actualiteC = new ActualiteC();
// ❗️ Afficher uniquement les actualités publiées (dont la date est <= NOW)
$liste = $actualiteC->afficherActualitesPubliées();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Actualités</title>
  <style>
    /* (CSS inchangé, déjà correct dans ton code) */
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

    .main h2 {
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

    .notification {
      position: fixed;
      top: 20px;
      right: 40px;
      background: #fff;
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      cursor: pointer;
      z-index: 1000;
    }

    .notification-icon {
      font-size: 24px;
      color: #333;
    }

    .notification-count {
      position: absolute;
      top: -5px;
      right: -5px;
      background: red;
      color: white;
      font-size: 14px;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      text-align: center;
      line-height: 20px;
    }
  </style>
  <script>
  // Fonction pour marquer toutes les actualités comme vues
  function marquerCommeVues() {
    fetch('marquer_vues.php')
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.getElementById('notification-count').style.display = 'none';

          // Mise à jour de la liste d'actualités non vues
          const actualites = data.actualites;
          const tbody = document.querySelector("tbody");
          tbody.innerHTML = '';

          if (actualites.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7">Aucune nouvelle actualité.</td></tr>';
          } else {
            actualites.forEach(row => {
              tbody.innerHTML += `
                <tr>
                  <td>${row.id_actualite}</td>
                  <td>${row.titre}</td>
                  <td>${row.contenu}</td>
                  <td><img src="${row.image_url}" alt="Image actualité" style="width: 80px; height: auto; border-radius: 5px;"></td>
                  <td>${row.date_publication}</td>
                  <td>${row.id_categorie}</td>
                  <td>
                    <a class="btn btn-modifier" href="modifierActualite.php?id=${row.id_actualite}">Modifier</a>
                    <a class="btn btn-supprimer" href="supprimerActualite.php?id=${row.id_actualite}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">Supprimer</a>
                  </td>
                </tr>
              `;
            });
          }
        }
      });
  }

  // Fonction pour charger les notifications
  function chargerNotifications() {
    fetch('notification.php')
      .then(response => response.json())
      .then(data => {
        const count = data.non_vues;
        const notifCount = document.getElementById('notification-count');
        if (count > 0) {
          notifCount.innerText = count;
          notifCount.style.display = 'block';
        } else {
          notifCount.style.display = 'none';
        }
      });
  }

  // Fonction pour charger les actualités non vues
  function chargerActualitesNonVues() {
    fetch('actualites_non_vues.php')
      .then(response => response.json())
      .then(data => {
        const tbody = document.querySelector("tbody");
        tbody.innerHTML = '';

        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="7">Aucune nouvelle actualité.</td></tr>';
        } else {
          data.forEach(row => {
            tbody.innerHTML += `
              <tr>
                <td>${row.id_actualite}</td>
                <td>${row.titre}</td>
                <td>${row.contenu}</td>
                <td><img src="${row.image_url}" alt="Image actualité" style="width: 80px; height: auto; border-radius: 5px;"></td>
                <td>${row.date_publication}</td>
                <td>${row.id_categorie}</td>
                <td>
                  <a class="btn btn-modifier" href="modifierActualite.php?id=${row.id_actualite}">Modifier</a>
                  <a class="btn btn-supprimer" href="supprimerActualite.php?id=${row.id_actualite}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">Supprimer</a>
                </td>
              </tr>
            `;
          });
        }
      });
  }

  // Exécuter lors du chargement de la page
  window.onload = function() {
    chargerNotifications();
  };
</script>

</head>
<body>



<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin</h2>
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

<!-- Contenu principal -->
<div class="main">
  <h2>Liste des actualités publiées</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Contenu</th>
        <th>Image</th>
        <th>Date</th>
        <th>Catégorie</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($liste)): ?>
        <?php foreach ($liste as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['id_actualite']) ?></td>
            <td><?= htmlspecialchars($row['titre']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['contenu'])) ?></td>
            <td>
              <?php if (!empty($row['image_url'])): ?>
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Image actualité">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['date_publication']) ?></td>
            <td><?= htmlspecialchars($row['id_categorie']) ?></td>
            <td>
              <a class="btn btn-modifier" href="modifierActualite.php?id=<?= $row['id_actualite'] ?>">Modifier</a>
              <a class="btn btn-supprimer" href="supprimerActualite.php?id=<?= $row['id_actualite'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7">Aucune actualité publiée pour le moment.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="ajouter_actualite.php" class="btn-ajouter">+</a>
</div>



</body>
</html>
