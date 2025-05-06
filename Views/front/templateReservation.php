<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';
require_once __DIR__ . '/../../Controller/CoursController.php';

$reservationController = new ReservationController();
$coursController = new CoursController();

// Récupérer la liste des statuts disponibles
$availableStatuses = $reservationController->getAvailableStatuses();

// Gérer le filtre par statut
$selectedStatus = isset($_GET['status']) ? $_GET['status'] : '';

// Handle search by date
$searchDate = null;
if (isset($_GET['search_date']) && !empty($_GET['search_date'])) {
    $searchDate = $_GET['search_date'];
    // Débogage
    error_log("Date de recherche reçue: " . $searchDate);

    // Si un statut est sélectionné, filtrer également par statut
    if (!empty($selectedStatus)) {
        $reservations = $reservationController->searchReservationsByDate($searchDate, $selectedStatus);
    } else {
        $reservations = $reservationController->searchReservationsByDate($searchDate);
    }
} else if (!empty($selectedStatus)) {
    // Filtrer uniquement par statut
    $reservations = $reservationController->filterReservationsByStatus($selectedStatus);
} else {
    // Aucun filtre
    $reservations = $reservationController->listReservations();
}

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

        .reservation-card.search-result {
            border-left: 4px solid #3498db;
            animation: highlight 1.5s ease-in-out;
        }

        @keyframes highlight {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.5); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
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
            background-image: linear-gradient(135deg, #2c3e50 0%, #4a6990 100%);
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

        .search-container {
            background-color: rgba(52, 73, 94, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-container h4 {
            font-weight: 600;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .search-container .form-control {
            border: none;
            background-color: rgba(255, 255, 255, 0.95);
            height: 50px;
            font-size: 16px;
        }

        .search-container .btn-light {
            background-color: white;
            border-color: white;
            color: #2c3e50;
            font-weight: bold;
            height: 50px;
        }

        .search-container .btn-outline-light {
            border-color: white;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
        }

        .search-container .btn-outline-light:hover {
            background-color: white;
            color: #2c3e50;
        }

        /* Styles pour les statuts */
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status-En.attente, .status-pending {
            background-color: #f39c12;
            color: white;
        }

        .status-Confirmé, .status-confirmed {
            background-color: #2ecc71;
            color: white;
        }

        .status-Payé {
            background-color: #3498db;
            color: white;
        }

        .status-Annulé {
            background-color: #e74c3c;
            color: white;
        }

        .status-Refusé {
            background-color: #c0392b;
            color: white;
        }

        /* Style pour le select */
        .form-select {
            height: 40px;
            border: none;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.95);
        }

        /* Styles pour le QR code */
        #qrcode-container {
            text-align: center;
            margin: 20px 0;
        }

        #qrcode-container canvas {
            border: 10px solid white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
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
            <div class="row">
                <div class="col-md-6">
                    <h1 class="section-title">Mes Réservations</h1>
                    <p class="section-description">
                        Gérez vos réservations de cours
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="search-container mt-4 p-4 rounded">
                        <h4 class="text-white mb-3"><i class="fas fa-search me-2"></i>Filtrer les réservations</h4>
                        <form action="templateReservation.php" method="GET" id="filterForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="search_date" class="form-label text-white">Par date</label>
                                    <input type="date" id="search_date" name="search_date" class="form-control"
                                           value="<?= htmlspecialchars($searchDate ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label text-white">Par statut</label>
                                    <select id="status" name="status" class="form-select">
                                        <option value="">Tous les statuts</option>
                                        <?php foreach ($availableStatuses as $status): ?>
                                            <option value="<?= htmlspecialchars($status) ?>" <?= $selectedStatus === $status ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($status) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-light">
                                    <i class="fas fa-filter me-2"></i> Appliquer les filtres
                                </button>
                                <?php if (!empty($searchDate) || !empty($selectedStatus)): ?>
                                <a href="templateReservation.php" class="btn btn-outline-light">
                                    <i class="fas fa-times me-2"></i> Réinitialiser
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations Section -->
    <div class="container mb-5">
        <?php if (!empty($searchDate) || !empty($selectedStatus)): ?>
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-filter me-2"></i>
                    <strong>
                        <?php if (!empty($searchDate) && !empty($selectedStatus)): ?>
                            Réservations du <?= date('d/m/Y', strtotime($searchDate)) ?> avec statut "<?= htmlspecialchars($selectedStatus) ?>"
                        <?php elseif (!empty($searchDate)): ?>
                            Réservations pour la date du <?= date('d/m/Y', strtotime($searchDate)) ?>
                        <?php elseif (!empty($selectedStatus)): ?>
                            Réservations avec statut "<?= htmlspecialchars($selectedStatus) ?>"
                        <?php endif; ?>
                    </strong>
                    (<?= count($reservations) ?> réservation(s) trouvée(s))
                </div>
                <a href="templateReservation.php" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-list me-1"></i> Voir toutes les réservations
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($reservations)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                <?php if (!empty($searchDate) && !empty($selectedStatus)): ?>
                    Aucune réservation trouvée pour la date du <?= date('d/m/Y', strtotime($searchDate)) ?> avec le statut "<?= htmlspecialchars($selectedStatus) ?>".
                <?php elseif (!empty($searchDate)): ?>
                    Aucune réservation trouvée pour la date du <?= date('d/m/Y', strtotime($searchDate)) ?>.
                <?php elseif (!empty($selectedStatus)): ?>
                    Aucune réservation trouvée avec le statut "<?= htmlspecialchars($selectedStatus) ?>".
                <?php else: ?>
                    Aucune réservation n'a été trouvée.
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <?php if (!empty($searchDate) || !empty($selectedStatus)): ?>
                    <a href="templateReservation.php" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Voir toutes les réservations
                    </a>
                <?php endif; ?>
                <a href="template2.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Réserver un cours
                </a>
            </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($reservations as $r): ?>
            <div class="col-md-4">
                <div class="card reservation-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= htmlspecialchars($r['type_cours']) ?></h5>
                        <span class="status-badge status-<?= str_replace(' ', '.', htmlspecialchars($r['statut'])) ?>">
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
                        <div class="mb-3">
                            <i class="fas fa-euro-sign me-2"></i>
                            Prix du cours: <?= htmlspecialchars($r['prix']) ?> €
                        </div>
                        <div class="d-flex flex-column mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <a href="editReservation.php?id=<?= $r['id_reservation'] ?>" class="btn btn-warning flex-grow-1 me-2">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                                <a href="?action=delete&id=<?= $r['id_reservation'] ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    <i class="fas fa-trash me-2"></i>Annuler
                                </a>
                            </div>
                            <div class="d-flex mb-2">
                                <button type="button" class="btn btn-info w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#qrCodeModal<?= $r['id_reservation'] ?>">
                                    <i class="fas fa-qrcode me-2"></i>Afficher QR Code
                                </button>
                            </div>
                            <?php if (strtolower($r['statut']) !== 'payé'): ?>
                            <form action="create-checkout-session.php" method="POST">
                                <input type="hidden" name="reservation_id" value="<?= $r['id_reservation'] ?>">
                                <input type="hidden" name="cours_prix" value="<?= $r['prix'] ?>">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-credit-card me-2"></i>Payer
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
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
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Script pour les filtres -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les éléments du formulaire
            const filterForm = document.getElementById('filterForm');
            const searchDateInput = document.getElementById('search_date');
            const statusSelect = document.getElementById('status');
            const urlParams = new URLSearchParams(window.location.search);

            // Mettre en évidence les résultats filtrés
            if (urlParams.has('search_date') || urlParams.has('status')) {
                // Ajouter une classe pour mettre en évidence les résultats de recherche
                const reservationCards = document.querySelectorAll('.reservation-card');
                reservationCards.forEach(card => {
                    card.classList.add('search-result');
                });
            }

            // Soumettre automatiquement le formulaire quand la date change
            searchDateInput.addEventListener('change', function() {
                filterForm.submit();
            });

            // Soumettre automatiquement le formulaire quand le statut change
            statusSelect.addEventListener('change', function() {
                filterForm.submit();
            });

            // Mettre en évidence les statuts correspondant au filtre
            if (urlParams.has('status')) {
                const selectedStatus = urlParams.get('status');
                const statusBadges = document.querySelectorAll('.status-badge');

                statusBadges.forEach(badge => {
                    if (badge.textContent.trim() === selectedStatus) {
                        badge.style.boxShadow = '0 0 8px rgba(0, 0, 0, 0.5)';
                        badge.style.transform = 'scale(1.05)';
                    }
                });
            }
        });
    </script>

    <!-- QR Code Modals -->
    <?php foreach ($reservations as $r): ?>
    <div class="modal fade" id="qrCodeModal<?= $r['id_reservation'] ?>" tabindex="-1" aria-labelledby="qrCodeModalLabel<?= $r['id_reservation'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel<?= $r['id_reservation'] ?>">
                        QR Code de la Réservation #<?= $r['id_reservation'] ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <?php
                    // Créer les données pour le QR code
                    $qrData = [
                        'id' => $r['id_reservation'],
                        'type_cours' => $r['type_cours'],
                        'date' => date('d/m/Y', strtotime($r['date_reservation'])),
                        'statut' => $r['statut'],
                        'prix' => $r['prix'],
                        'nombre_personnes' => $r['nombre_personnes']
                    ];

                    // Encoder les données en JSON et les encoder pour l'URL
                    $qrDataEncoded = urlencode(json_encode($qrData));

                    // Générer l'URL de l'API QR Code
                    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $qrDataEncoded;
                    ?>

                    <div class="d-inline-block p-3 bg-white">
                        <img src="<?= $qrCodeUrl ?>" alt="QR Code Réservation #<?= $r['id_reservation'] ?>" class="img-fluid" id="qrcode-img-<?= $r['id_reservation'] ?>">
                    </div>

                    <div class="mt-3">
                        <h6>Informations de la réservation :</h6>
                        <p class="mb-1"><strong>Cours :</strong> <?= htmlspecialchars($r['type_cours']) ?></p>
                        <p class="mb-1"><strong>Date :</strong> <?= date('d/m/Y', strtotime($r['date_reservation'])) ?></p>
                        <p class="mb-1"><strong>Statut :</strong> <?= htmlspecialchars($r['statut']) ?></p>
                        <p class="mb-1"><strong>Prix :</strong> <?= htmlspecialchars($r['prix']) ?> €</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="<?= $qrCodeUrl ?>" download="reservation-<?= $r['id_reservation'] ?>-qrcode.png" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Télécharger
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Payment Modals -->
    <?php foreach ($reservations as $r): ?>
    <?php if (strtolower($r['statut']) !== 'payé'): ?>
    <div class="modal fade" id="paymentModal<?= $r['id_reservation'] ?>" tabindex="-1" aria-labelledby="paymentModalLabel<?= $r['id_reservation'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel<?= $r['id_reservation'] ?>">Paiement de la Réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="payment-form-<?= $r['id_reservation'] ?>" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="card-name-<?= $r['id_reservation'] ?>" class="form-label">Nom sur la Carte</label>
                                <input type="text" class="form-control" id="card-name-<?= $r['id_reservation'] ?>" placeholder="Nom complet" required>
                                <div class="invalid-feedback">Veuillez entrer le nom sur la carte</div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="card-element-<?= $r['id_reservation'] ?>" class="form-label">Informations de Carte de Crédit</label>
                                <div id="card-element-<?= $r['id_reservation'] ?>" class="form-control">
                                    <!-- Stripe Elements Placeholder -->
                                </div>
                                <div id="card-errors-<?= $r['id_reservation'] ?>" role="alert" class="text-danger mt-2"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="save-card-<?= $r['id_reservation'] ?>">
                                    <label class="form-check-label" for="save-card-<?= $r['id_reservation'] ?>">
                                        Enregistrer cette carte pour les futurs paiements
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-lock me-2"></i>Payer <?= htmlspecialchars(isset($r['prix']) ? $r['prix'] : '0') ?>€
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>



    <script>
        // Stripe Payment Integration
        var stripe = Stripe('pk_test_51PGnmBLwKQmqD8Ux5Ld9qLHEcIFqMtqaWnqCvtGGzFqBnQXZqYXGkHvLYjQoYZZAJqKkNxDzjQZFGQqsGVQHvMJT00qVMbVnEy');
        var elements = stripe.elements();

        <?php foreach ($reservations as $r): ?>
        <?php if (strtolower($r['statut']) !== 'payé'): ?>
        var cardElement<?= $r['id_reservation'] ?> = elements.create('card');
        cardElement<?= $r['id_reservation'] ?>.mount('#card-element-<?= $r['id_reservation'] ?>');

        var form<?= $r['id_reservation'] ?> = document.getElementById('payment-form-<?= $r['id_reservation'] ?>');
        form<?= $r['id_reservation'] ?>.addEventListener('submit', function(event) {
            event.preventDefault();

            // Form validation
            if (!form<?= $r['id_reservation'] ?>.checkValidity()) {
                event.stopPropagation();
                form<?= $r['id_reservation'] ?>.classList.add('was-validated');
                return;
            }

            // Get cardholder name
            var cardholderName = document.getElementById('card-name-<?= $r['id_reservation'] ?>').value;

            // Create payment method
            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement<?= $r['id_reservation'] ?>,
                billing_details: {
                    name: cardholderName
                }
            }).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors-<?= $r['id_reservation'] ?>');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the payment method to your server
                    fetch('process_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            paymentMethodId: result.paymentMethod.id,
                            reservationId: <?= $r['id_reservation'] ?>,
                            amount: <?= isset($r['prix']) ? $r['prix'] * 100 : 0 ?>, // Amount in cents
                            saveCard: document.getElementById('save-card-<?= $r['id_reservation'] ?>').checked
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Paiement réussi!');
                            window.location.reload();
                        } else {
                            alert('Erreur de paiement: ' + data.message);
                        }
                    });
                }
            });
        });

        // Form validation initialization
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        <?php endif; ?>
        <?php endforeach; ?>
    </script>
</body>
</html>
