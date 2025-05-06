<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';
require_once __DIR__ . '/../../Controller/CoursController.php';

$reservationController = new ReservationController();
$coursController = new CoursController();

// Get the reservation ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header('Location: templateReservation.php');
    exit();
}

// Get the reservation details
$reservation = $reservationController->getReservationById($id);
if (!$reservation) {
    header('Location: templateReservation.php');
    exit();
}

// Get all courses for the dropdown
$cours = $coursController->listCours();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'date_reservation' => $_POST['date_reservation'],
        'nombre_personnes' => $_POST['nombre_personnes'],
        'statut' => $reservation['statut'], // Keep the existing status
        'id_cours' => $_POST['id_cours']
    ];

    if ($reservationController->updateReservation($id, $data)) {
        header('Location: templateReservation.php?success=2');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Réservation</title>
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

        .edit-form {
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

        .btn-warning {
            padding: 12px 25px;
            font-weight: 600;
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

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-pending {
            background-color: #f1c40f;
            color: #fff;
        }

        .status-confirmed {
            background-color: #2ecc71;
            color: #fff;
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
            <h1 class="section-title">Modifier la Réservation</h1>
            <p class="section-description">
                Modifiez les détails de votre réservation
                <span class="status-badge <?= $reservation['statut'] === 'Confirmé' ? 'status-confirmed' : 'status-pending' ?>">
                    <?= htmlspecialchars($reservation['statut']) ?>
                </span>
            </p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="edit-form">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="id_cours" class="form-label">Cours</label>
                            <select class="form-select" id="id_cours" name="id_cours" required>
                                <option value="">Sélectionnez un cours</option>
                                <?php foreach ($cours as $c): ?>
                                    <option value="<?= $c['id_cours'] ?>" 
                                            <?= $c['id_cours'] == $reservation['id_cours'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['type_cours']) ?> - 
                                        <?= htmlspecialchars($c['date_cours']) ?> - 
                                        <?= htmlspecialchars($c['prix']) ?>€
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un cours
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="date_reservation" class="form-label">Date de Réservation</label>
                            <input type="date" class="form-control" id="date_reservation" 
                                   name="date_reservation" 
                                   value="<?= htmlspecialchars($reservation['date_reservation']) ?>" 
                                   required min="<?= date('Y-m-d') ?>">
                            <div class="invalid-feedback">
                                Veuillez choisir une date valide
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nombre_personnes" class="form-label">Nombre de Personnes</label>
                            <input type="number" class="form-control" id="nombre_personnes" 
                                   name="nombre_personnes" min="1" 
                                   value="<?= htmlspecialchars($reservation['nombre_personnes']) ?>" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un nombre valide de personnes
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                <i class="fas fa-credit-card me-2"></i>Payer
                            </button>
                            <a href="templateReservation.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux Réservations
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    
    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Paiement de la Réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="payment-form">
                        <div class="form-group mb-3">
                            <label for="card-element" class="form-label">Informations de Carte de Crédit</label>
                            <div id="card-element" class="form-control">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Payer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        // Stripe Payment Integration
        var stripe = Stripe('pk_test_51PGnmBLwKQmqD8Ux5Ld9qLHEcIFqMtqaWnqCvtGGzFqBnQXZqYXGkHvLYjQoYZZAJqKkNxDzjQZFGQqsGVQHvMJT00qVMbVnEy');
        var elements = stripe.elements();

        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server
                    fetch('process_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            token: result.token.id,
                            reservationId: <?= $id ?>,
                            amount: <?= $reservation['prix'] * 100 ?> // Amount in cents
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Paiement réussi!');
                            window.location.href = 'templateReservation.php';
                        } else {
                            alert('Erreur de paiement: ' + data.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
