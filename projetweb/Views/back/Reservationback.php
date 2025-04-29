<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';
require_once __DIR__ . '/../../Controller/CoursController.php';

$reservationController = new ReservationController();
$coursController = new CoursController();

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $reservationController->deleteReservation($_GET['id']);
    header('Location: Reservationback.php?success=1');
    exit();
}

// Get all reservations
$reservations = $reservationController->listReservations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            padding: 20px;
        }
        .active {
            background-color: #495057;
        }
        .reservation-item {
            border-left: 4px solid #343a40;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .reservation-item:hover {
            border-left-color: #dc3545;
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        .status-pending {
            background-color: #ffeeba;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h3 class="text-white p-3">Dashboard</h3>
                <nav>
                    <a href="template.php">
                        <i class="fas fa-book me-2"></i> Gestion des Cours
                    </a>
                    <a href="Reservationback.php" class="active">
                        <i class="fas fa-calendar-check me-2"></i> Réservations
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <h2 class="mb-4">Gestion des Réservations</h2>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        La réservation a été supprimée avec succès.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (empty($reservations)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune réservation n'a été trouvée.
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <div class="row align-items-center">
                                <div class="col">
                                    <i class="fas fa-list me-2"></i>
                                    Liste des Réservations (<?= count($reservations) ?>)
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php foreach ($reservations as $reservation): ?>
                                <div class="card reservation-item">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h5 class="card-title">
                                                    <i class="fas fa-user me-2"></i>
                                                    <?= htmlspecialchars($reservation['type_cours']) ?>
                                                </h5>
                                                <small class="text-muted">
                                                    ID: #<?= htmlspecialchars($reservation['id_reservation']) ?>
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    <?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?>
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-users me-2"></i>
                                                    <?= htmlspecialchars($reservation['nombre_personnes']) ?> personne(s)
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="status-badge <?= $reservation['statut'] === 'Confirmé' ? 'status-confirmed' : 'status-pending' ?>">
                                                    <i class="fas <?= $reservation['statut'] === 'Confirmé' ? 'fa-check-circle' : 'fa-clock' ?> me-2"></i>
                                                    <?= htmlspecialchars($reservation['statut']) ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <a href="?action=delete&id=<?= $reservation['id_reservation'] ?>" 
                                                   class="btn btn-danger"
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
