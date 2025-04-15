<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Back Office - Forum</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
    header, nav { background: #222; color: white; padding: 15px; text-align: center; }
    nav a { color: white; margin: 0 10px; text-decoration: none; }
    main { max-width: 1000px; margin: auto; padding: 30px; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; }
    .action-btn { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
    .delete { background: #e74c3c; color: white; }
    .disable { background: #f1c40f; color: black; }
  </style>
</head>
<body>
  <header><h1>🔧 Gestion des Commentaires</h1></header>
  <nav><a href="#">Dashboard</a><a href="#">Utilisateurs</a><a href="#">Forum</a><a href="#">Déconnexion</a></nav>
  <main>
    <h2>Commentaires publiés</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Auteur</th><th>Commentaire</th><th>Date</th><th>Statut</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commentaires as $commentaire): ?>
          <tr>
            <td><?= $commentaire['id'] ?></td>
            <td><?= htmlspecialchars($commentaire['auteur']) ?></td>
            <td><?= htmlspecialchars($commentaire['contenu']) ?></td>
            <td><?= $commentaire['date'] ?></td>
            <td><?= $commentaire['statut'] ?></td>
            <td>
              <a class="action-btn disable" href="?action=disable&id=<?= $commentaire['id'] ?>">Désactiver</a>
              <a class="action-btn delete" href="?action=delete&id=<?= $commentaire['id'] ?>">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
