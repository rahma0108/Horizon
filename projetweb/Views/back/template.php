<?php
require_once __DIR__ . '/../../Controller/CoursController.php';
require_once __DIR__ . '/../../Model/Cours.php';

$coursController = new CoursController();

// Gérer les actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Get statistics data
$stats = $coursController->getCourseStatistics();
$typeDistribution = $coursController->getCourseTypeDistribution();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion des Cours</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .stats-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            margin-top: 20px;
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
                    <a href="template.php" class="<?php echo !isset($_GET['action']) ? 'active' : ''; ?>">
                        <i class="fas fa-book me-2"></i> Gestion des Cours
                    </a>
                    <a href="?action=stats" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'stats' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-bar me-2"></i> Statistiques
                    </a>
                    <a href="Reservationback.php">
                        <i class="fas fa-calendar-check me-2"></i> Réservations
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <?php
                switch($action) {
                    case 'stats':
                        ?>
                        <h2>Statistiques des Cours</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <h4>Distribution par Type</h4>
                                    <div class="chart-container">
                                        <canvas id="typeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <h4>Analyse des Prix</h4>
                                    <div class="chart-container">
                                        <canvas id="priceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <h4>Total des Cours</h4>
                                    <h2 class="text-primary"><?= $stats['total_courses'] ?></h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <h4>Prix Moyen</h4>
                                    <h2 class="text-success"><?= number_format($stats['average_price'], 2) ?> €</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <h4>Types de Cours Uniques</h4>
                                    <h2 class="text-info"><?= $stats['unique_types'] ?></h2>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;

                    case 'add':
                        // Formulaire d'ajout
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
                            $cours = new Cours();
                            $cours->setType($_POST['type_cours']);
                            $cours->setDate($_POST['date_cours']);
                            $cours->setAdresse($_POST['adresse']);
                            $cours->setPrix($_POST['prix']);
                            
                            if ($coursController->addCours($cours)) {
                                echo '<div class="alert alert-success">Cours ajouté avec succès!</div>';
                                header("Location: template.php");
                                exit();
                            }
                        }
                        ?>
                        <h2>Ajouter un Cours</h2>
                        <form method="POST" class="mt-3">
                            <div class="mb-3">
                                <label for="type_cours" class="form-label">Type de Cours</label>
                                <input type="text" class="form-control" id="type_cours" name="type_cours" required>
                            </div>
                            <div class="mb-3">
                                <label for="date_cours" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date_cours" name="date_cours" required>
                            </div>
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                            <div class="mb-3">
                                <label for="prix" class="form-label">Prix</label>
                                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                            </div>
                            <button type="submit" name="add" class="btn btn-primary">Ajouter</button>
                            <a href="template.php" class="btn btn-secondary">Retour</a>
                        </form>
                        <?php
                        break;

                    case 'edit':
                        // Formulaire de modification
                        $id = isset($_GET['id']) ? $_GET['id'] : null;
                        if ($id) {
                            $cours = $coursController->getCoursById($id);
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
                                $data = [
                                    'type_cours' => $_POST['type_cours'],
                                    'date_cours' => $_POST['date_cours'],
                                    'adresse' => $_POST['adresse'],
                                    'prix' => $_POST['prix']
                                ];
                                if ($coursController->updateCours($id, $data)) {
                                    echo '<div class="alert alert-success">Cours modifié avec succès!</div>';
                                    header("Location: template.php");
                                    exit();
                                }
                            }
                            ?>
                            <h2>Modifier un Cours</h2>
                            <form method="POST" class="mt-3">
                                <div class="mb-3">
                                    <label for="type_cours" class="form-label">Type de Cours</label>
                                    <input type="text" class="form-control" id="type_cours" name="type_cours" 
                                           value="<?= htmlspecialchars($cours['type_cours']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="date_cours" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date_cours" name="date_cours" 
                                           value="<?= htmlspecialchars($cours['date_cours']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" 
                                           value="<?= htmlspecialchars($cours['adresse']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix</label>
                                    <input type="number" step="0.01" class="form-control" id="prix" name="prix" 
                                           value="<?= htmlspecialchars($cours['prix']) ?>" required>
                                </div>
                                <button type="submit" name="edit" class="btn btn-primary">Modifier</button>
                                <a href="template.php" class="btn btn-secondary">Retour</a>
                            </form>
                            <?php
                        }
                        break;

                    case 'delete':
                        // Suppression
                        $id = isset($_GET['id']) ? $_GET['id'] : null;
                        if ($id && $coursController->deleteCours($id)) {
                            echo '<div class="alert alert-success">Cours supprimé avec succès!</div>';
                        }
                        header("Location: template.php");
                        exit();
                        break;

                    default:
                        // Liste des cours
                        $cours = $coursController->listCours();
                        ?>
                        <h2>Liste des Cours</h2>
                        <a href="?action=add" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i> Ajouter un Cours
                        </a>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Adresse</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cours as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['id_cours']) ?></td>
                                    <td><?= htmlspecialchars($c['type_cours']) ?></td>
                                    <td><?= htmlspecialchars($c['date_cours']) ?></td>
                                    <td><?= htmlspecialchars($c['adresse']) ?></td>
                                    <td><?= htmlspecialchars($c['prix']) ?> €</td>
                                    <td>
                                        <a href="?action=edit&id=<?= $c['id_cours'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="?action=delete&id=<?= $c['id_cours'] ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if ($action === 'stats'): ?>
    <script>
        // Type Distribution Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($typeDistribution, 'type_cours')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($typeDistribution, 'count')) ?>,
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e74c3c',
                        '#f1c40f',
                        '#9b59b6',
                        '#1abc9c'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Price Analysis Chart
        const priceCtx = document.getElementById('priceChart').getContext('2d');
        new Chart(priceCtx, {
            type: 'bar',
            data: {
                labels: ['Minimum', 'Moyen', 'Maximum'],
                datasets: [{
                    label: 'Prix (€)',
                    data: [
                        <?= $stats['min_price'] ?>,
                        <?= $stats['average_price'] ?>,
                        <?= $stats['max_price'] ?>
                    ],
                    backgroundColor: [
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(231, 76, 60, 0.7)'
                    ],
                    borderColor: [
                        'rgb(46, 204, 113)',
                        'rgb(52, 152, 219)',
                        'rgb(231, 76, 60)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Prix (€)'
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
