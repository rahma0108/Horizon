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
                                <div class="input-group">
                                    <input type="text" class="form-control" id="adresse" name="adresse" required>
                                    <button type="button" class="btn btn-info" id="selectMapBtn" data-bs-toggle="modal" data-bs-target="#mapModal">
                                        <i class="fas fa-map-marker-alt me-2"></i>Sélectionner sur la carte
                                    </button>
                                </div>
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
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="adresse" name="adresse"
                                               value="<?= htmlspecialchars($cours['adresse']) ?>" required>
                                        <button type="button" class="btn btn-info" id="selectMapBtn" data-bs-toggle="modal" data-bs-target="#mapModal">
                                            <i class="fas fa-map-marker-alt me-2"></i>Sélectionner sur la carte
                                        </button>
                                    </div>
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

    <!-- Modal pour la carte OpenStreetMap -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Sélectionner une adresse sur la carte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchAddress" placeholder="Rechercher une adresse...">
                            <button class="btn btn-primary" type="button" id="searchAddressBtn">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </div>
                    </div>
                    <div id="map" style="width: 100%; height: 400px;"></div>
                    <div class="mt-3">
                        <p><strong>Adresse sélectionnée:</strong> <span id="selectedAddress">Aucune adresse sélectionnée</span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmAddressBtn">Confirmer cette adresse</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

    <!-- Script pour la carte OpenStreetMap -->
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser la carte
            let map = null;
            let marker = null;
            let selectedLat = 48.8566; // Paris par défaut
            let selectedLng = 2.3522;
            let selectedAddress = "<?= isset($cours['adresse']) ? addslashes($cours['adresse']) : '' ?>";

            // Initialiser la carte quand la modal est ouverte
            document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
                if (!map) {
                    initMap();
                }

                // Si on a déjà une adresse, essayer de la géocoder
                const currentAddress = document.getElementById('adresse').value;
                if (currentAddress) {
                    document.getElementById('searchAddress').value = currentAddress;
                    geocode(currentAddress);
                }
            });

            function initMap() {
                // Créer la carte
                map = L.map('map').setView([selectedLat, selectedLng], 13);

                // Ajouter la couche OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Ajouter un événement de clic sur la carte
                map.on('click', function(e) {
                    selectedLat = e.latlng.lat;
                    selectedLng = e.latlng.lng;

                    // Mettre à jour ou créer le marqueur
                    if (marker) {
                        marker.setLatLng(e.latlng);
                    } else {
                        marker = L.marker(e.latlng, {draggable: true}).addTo(map);
                        marker.on('dragend', onMarkerDragEnd);
                    }

                    // Récupérer l'adresse à partir des coordonnées (reverse geocoding)
                    reverseGeocode(selectedLat, selectedLng);
                });
            }

            // Fonction appelée quand le marqueur est déplacé
            function onMarkerDragEnd(event) {
                let position = marker.getLatLng();
                selectedLat = position.lat;
                selectedLng = position.lng;

                // Récupérer l'adresse à partir des coordonnées
                reverseGeocode(selectedLat, selectedLng);
            }

            // Fonction pour faire du reverse geocoding (coordonnées -> adresse)
            function reverseGeocode(lat, lng) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            selectedAddress = data.display_name;
                            document.getElementById('selectedAddress').textContent = selectedAddress;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du reverse geocoding:', error);
                    });
            }

            // Fonction pour faire du geocoding (adresse -> coordonnées)
            function geocode(address) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            selectedLat = parseFloat(data[0].lat);
                            selectedLng = parseFloat(data[0].lon);
                            selectedAddress = data[0].display_name;

                            // Centrer la carte sur la position trouvée
                            map.setView([selectedLat, selectedLng], 15);

                            // Mettre à jour ou créer le marqueur
                            if (marker) {
                                marker.setLatLng([selectedLat, selectedLng]);
                            } else {
                                marker = L.marker([selectedLat, selectedLng], {draggable: true}).addTo(map);
                                marker.on('dragend', onMarkerDragEnd);
                            }

                            document.getElementById('selectedAddress').textContent = selectedAddress;
                        } else {
                            alert('Adresse non trouvée');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du geocoding:', error);
                    });
            }

            // Événement pour le bouton de recherche d'adresse
            document.getElementById('searchAddressBtn').addEventListener('click', function() {
                const address = document.getElementById('searchAddress').value;
                if (address) {
                    geocode(address);
                }
            });

            // Événement pour la touche Entrée dans le champ de recherche
            document.getElementById('searchAddress').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const address = document.getElementById('searchAddress').value;
                    if (address) {
                        geocode(address);
                    }
                }
            });

            // Événement pour le bouton de confirmation d'adresse
            document.getElementById('confirmAddressBtn').addEventListener('click', function() {
                if (selectedAddress) {
                    document.getElementById('adresse').value = selectedAddress;

                    // Fermer la modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
                    modal.hide();
                } else {
                    alert('Veuillez sélectionner une adresse sur la carte');
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
