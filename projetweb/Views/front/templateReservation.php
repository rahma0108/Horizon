<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';
require_once __DIR__ . '/../../Controller/CoursController.php';

$reservationController = new ReservationController();
$coursController = new CoursController();
$reservations = $reservationController->listReservations();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $reservationController->deleteReservation($_GET['id']);
    header('Location: templateReservation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Keeping the same style as template2.php */
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

        .reservation-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .reservation-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 15px;
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

        .status-pending {
            background-color: #f1c40f;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .status-confirmed {
            background-color: #2ecc71;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
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
                        <a class="nav-link" href="template2.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="template2.php">Cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="templateReservation.php">Réservations</a>
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
            <h1 class="section-title">Mes Réservations</h1>
            <p class="section-description">
                Gérez vos réservations de cours
            </p>
        </div>
    </div>

    <!-- Reservations Section -->
    <div class="container mb-5">
        <div class="row">
            <?php foreach ($reservations as $r): ?>
            <div class="col-md-4">
                <div class="card reservation-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= htmlspecialchars($r['type_cours']) ?></h5>
                        <span class="status-<?= strtolower($r['statut']) === 'confirmé' ? 'confirmed' : 'pending' ?>">
                            <?= htmlspecialchars($r['statut']) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Date de réservation: <?= date('d/m/Y', strtotime($r['date_reservation'])) ?>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-users me-2"></i>
                            Nombre de personnes: <?= htmlspecialchars($r['nombre_personnes']) ?>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="editReservation.php?id=<?= $r['id_reservation'] ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <a href="?action=delete&id=<?= $r['id_reservation'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                <i class="fas fa-trash me-2"></i>Annuler
                            </a>
                        </div>
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
