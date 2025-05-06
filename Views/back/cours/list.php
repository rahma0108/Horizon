<?php
require_once __DIR__ . '/../../../Controller/CoursController.php';
$coursController = new CoursController();
$cours = $coursController->listCours();
?>

<div class="container-fluid">
    <h2 class="mb-4">Gestion des Cours</h2>
    <a href="?page=cours&action=add" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Ajouter un Cours
    </a>
    
    <div class="card">
        <div class="card-body">
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
                            <a href="?page=cours&action=view&id=<?= $c['id_cours'] ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?page=cours&action=edit&id=<?= $c['id_cours'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?page=cours&action=delete&id=<?= $c['id_cours'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>