<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';
require_once __DIR__ . '/../../Controller/CoursController.php';
require_once __DIR__ . '/../../Model/Reservation.php';

$reservationController = new ReservationController();
$coursController = new CoursController();

// Get the course ID from URL if passed
$id_cours = isset($_GET['id_cours']) ? $_GET['id_cours'] : null;
$selectedCours = $id_cours ? $coursController->getCoursById($id_cours) : null;

// Get all courses for the dropdown if no specific course is selected
if (!$id_cours) {
    $cours = $coursController->listCours();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation = new Reservation();
    $reservation->setDateReservation($_POST['date_reservation']);
    $reservation->setNombrePersonnes($_POST['nombre_personnes']);
    $reservation->setStatut('En attente'); // Default status for new reservations
    $reservation->setIdCours($_POST['id_cours']);

    if ($reservationController->addReservation($reservation)) {
        header('Location: templateReservation.php?success=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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

        .reservation-form {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px 25px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #2980b9;
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
            <h1 class="section-title">Réserver un Cours</h1>
            <p class="section-description">
                Remplissez le formulaire ci-dessous pour réserver votre place
            </p>
        </div>
    </div>

    <!-- Reservation Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="reservation-form">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="id_cours" class="form-label">Sélectionnez le Cours</label>
                            <?php if ($selectedCours): ?>
                                <input type="hidden" name="id_cours" value="<?= $selectedCours['id_cours'] ?>">
                                <div class="form-control bg-light">
                                    <?= htmlspecialchars($selectedCours['type_cours']) ?> - 
                                    <?= htmlspecialchars($selectedCours['date_cours']) ?> - 
                                    <?= htmlspecialchars($selectedCours['prix']) ?>€
                                </div>
                            <?php else: ?>
                                <select class="form-select" id="id_cours" name="id_cours" required>
                                    <option value="">Choisissez un cours</option>
                                    <?php foreach ($cours as $c): ?>
                                        <option value="<?= $c['id_cours'] ?>">
                                            <?= htmlspecialchars($c['type_cours']) ?> - 
                                            <?= htmlspecialchars($c['date_cours']) ?> - 
                                            <?= htmlspecialchars($c['prix']) ?>€
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un cours
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="date_reservation" class="form-label">Date de Réservation</label>
                            <input type="date" class="form-control" id="date_reservation" 
                                   name="date_reservation" required min="<?= date('Y-m-d') ?>">
                            <div class="invalid-feedback">
                                Veuillez choisir une date valide
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nombre_personnes" class="form-label">Nombre de Personnes</label>
                            <input type="number" class="form-control" id="nombre_personnes" 
                                   name="nombre_personnes" min="1" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un nombre valide de personnes
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Confirmer la Réservation
                            </button>
                            <a href="template2.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux Cours
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
