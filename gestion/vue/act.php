<?php
$siteTitle = "Fitsense - Club Multisports";
include '../config.php';
include '../controller/actualiteC.php';

$actualiteC = new ActualiteC();
$showNewOnly = isset($_GET['show_new']) && $_GET['show_new'] == '1';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if ($showNewOnly) {
        if ($search !== '') {
            $stmt = $pdo->prepare("SELECT a.* FROM actualites a
                                   INNER JOIN categorie_actualite c ON a.id_categorie = c.id_categorie
                                   WHERE a.vu = 0 AND a.titre LIKE :search AND a.date_publication <= NOW()
                                   ORDER BY a.date_publication DESC");
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt = $pdo->prepare("SELECT a.* FROM actualites a
                                   INNER JOIN categorie_actualite c ON a.id_categorie = c.id_categorie
                                   WHERE a.vu = 0 AND a.date_publication <= NOW()
                                   ORDER BY a.date_publication DESC");
            $stmt->execute();
        }
        $pdo->query("UPDATE actualites SET vu = 1 WHERE vu = 0");
        $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        if ($search !== '') {
            $stmt = $pdo->prepare("SELECT a.* FROM actualites a
                                   INNER JOIN categorie_actualite c ON a.id_categorie = c.id_categorie
                                   WHERE a.titre LIKE :search AND a.date_publication <= NOW()
                                   ORDER BY a.date_publication DESC");
            $stmt->execute(['search' => "%$search%"]);
            $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $actualites = $actualiteC->afficherActualitesPubliées();
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteTitle) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .navbar {
            position: fixed; top: 0; width: 100%;
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 50px;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
        }
        .navbar:hover { background: rgba(0,0,0,0.85); }
        .navbar .logo { font-size: 2rem; font-weight: 700; color: #fff; }
        .navbar .menu a {
            color: #fff; text-decoration: none; margin: 0 20px; font-size: 1.1rem;
        }
        .navbar .menu a:hover { color: #c5ff38; }

        .hero {
            padding-top: 130px;
            padding-bottom: 60px;
            background: rgba(0,0,0,0.6);
            min-height: 100vh;
        }
        .hero .search-form {
            text-align: center;
            margin-bottom: 30px;
        }
        .hero .search-form input[type="text"] {
            padding: 10px; border-radius: 5px; border: none;
            width: 300px; max-width: 90%;
        }
        .hero .search-form button {
            padding: 10px 20px; background-color: #c5ff38;
            border: none; border-radius: 5px; cursor: pointer;
            margin-left: 10px;
        }
        .hero .search-form a {
            margin-left: 10px; color: white;
            text-decoration: underline;
        }

        .hero .cards {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;
            padding: 20px;
        }
        .card {
            width: 300px; background: #fff; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            color: #333;
        }
        .card img {
            width: 100%; height: 180px; object-fit: cover;
        }
        .card .content {
            padding: 15px;
        }
        .card h3 { margin: 0; font-size: 18px; }
        .card p { font-size: 14px; margin-top: 10px; }
        .card small { color: #777; }

        .notification {
            position: fixed; top: 110px; right: 40px;
            background: #fff; padding: 10px; border-radius: 50%;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            cursor: pointer; z-index: 1001;
        }
        .notification-icon {
            font-size: 24px; color: #333;
        }
        .notification-count {
            position: absolute; top: -5px; right: -5px;
            background: red; color: white; font-size: 14px;
            width: 20px; height: 20px; border-radius: 50%;
            text-align: center; line-height: 20px;
            display: none;
        }
        .footer {
            position: fixed; bottom: 0; width: 100%;
            background: rgba(0,0,0,0.8);
            text-align: center; padding: 10px 20px;
            font-size: 0.9rem;
        }
        .footer a { color: #c5ff38; text-decoration: none; }
        .footer a:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            .navbar { padding: 15px; }
            .hero .cards { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

<!-- 🔔 Notification -->
<div class="notification" onclick="afficherNouvellesActualites()">
    <span class="notification-icon">🔔</span>
    <span class="notification-count" id="notification-count"></span>
</div>

<nav class="navbar">
    <div class="logo">greenmove</div>
    <div class="menu">
        <a href="#">Accueil</a>
        <a href="act.php">Actualités</a>
        <a href="#">Événements</a>
        <a href="#">Forum</a>
        <a href="#">Magasin</a>
        <a href="#">Activités</a>
    </div>
</nav>

<section class="hero">
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Rechercher un titre..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Rechercher</button>
        <?php if ($search !== ''): ?>
            <a href="act.php">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <div class="cards">
        <?php if ($showNewOnly && empty($actualites)): ?>
            <p style="color:white; text-align:center;">Aucune nouvelle actualité.</p>
        <?php else: ?>
            <?php foreach ($actualites as $actu): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($actu['image_url']) ?>" alt="Image actualité">
                    <div class="content">
                        <h3><?= htmlspecialchars($actu['titre']) ?></h3>
                        <p><?= nl2br(htmlspecialchars(substr($actu['contenu'], 0, 100))) ?>...</p>
                        <small>Publié le : <?= htmlspecialchars($actu['date_publication']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2025 Fitsense - Tous droits réservés. <a href="#">Mentions légales</a></p>
</footer>

<script>
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

    function afficherNouvellesActualites() {
        window.location.href = 'act.php?show_new=1';
    }

    window.onload = function() {
        chargerNotifications();
        <?php if ($showNewOnly): ?>
            document.getElementById('notification-count').style.display = 'none';
        <?php endif; ?>
    };
</script>

</body>
</html>
