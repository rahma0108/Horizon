<?php
require_once __DIR__ . '/../../Controller/CoursController.php';
require_once __DIR__ . '/../../Model/Cours.php';

$coursController = new CoursController();

// Gérer les actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
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
                    <a href="Reservationback.php">
                        <i class="fas fa-calendar-check me-2"></i> Réservations
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <?php
                switch($action) {
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
</body>
</html>
