<?php
require_once __DIR__ . '/../../Controller/CoursController.php';
require_once __DIR__ . '/../../Model/Cours.php';

$coursController = new CoursController();

// Gérer les actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$cours = $coursController->listCours();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Style moderne pour le front-office */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: #2c3e50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .course-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 15px;
        }

        .price-tag {
            background-color: #2ecc71;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .course-info {
            padding: 20px;
        }

        .course-date {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .location-icon {
            color: #e74c3c;
        }

        .header-section {
            background-color: #2c3e50;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-description {
            font-size: 1.2rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Centre de Formation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="templateReservation.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <h1 class="section-title">Découvrez Nos Cours</h1>
            <p class="section-description">
                Explorez notre sélection de cours de qualité pour développer vos compétences
            </p>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="container mb-5">
        <div class="row">
            <?php foreach ($cours as $c): ?>
            <div class="col-md-4">
                <div class="card course-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?= htmlspecialchars($c['type_cours']) ?></h5>
                    </div>
                    <div class="card-body course-info">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="course-date">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <?= date('d/m/Y', strtotime($c['date_cours'])) ?>
                            </span>
                            <span class="price-tag">
                                <?= number_format($c['prix'], 2) ?> €
                            </span>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt location-icon me-2"></i>
                            <?= htmlspecialchars($c['adresse']) ?>
                        </p>
                        <button class="btn btn-primary w-100" onclick="window.location.href='addReservation.php?id_cours=<?= $c['id_cours'] ?>'">
                            <i class="fas fa-calendar-check me-2"></i>Réserver
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>À propos de nous</h5>
                    <p>Centre de formation professionnel proposant des cours de qualité.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>
                        <i class="fas fa-phone me-2"></i> +33 1 23 45 67 89<br>
                        <i class="fas fa-envelope me-2"></i> contact@formation.com
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Suivez-nous</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
