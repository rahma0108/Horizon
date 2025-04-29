<?php
require_once '../../../Controller/CoursController.php';
require_once '../../../Model/Cours.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cours = new Cours();
    $cours->setType($_POST['type_cours']);
    $cours->setDate($_POST['date_cours']);
    $cours->setAdresse($_POST['adresse']);
    $cours->setPrix($_POST['prix']);

    $controller = new CoursController();
    if ($controller->addCours($cours)) {
        header('Location: list.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Cours</h2>
        <form method="POST" class="mt-3 needs-validation" novalidate>
            <div class="mb-3">
                <label for="type_cours" class="form-label">Type de Cours</label>
                <input type="text" class="form-control" id="type_cours" name="type_cours" required>
                <div class="invalid-feedback custom-feedback">
                    Veuillez remplir ce champ
                </div>
            </div>
            <div class="mb-3">
                <label for="date_cours" class="form-label">Date</label>
                <input type="date" class="form-control" id="date_cours" name="date_cours" required>
                <div class="invalid-feedback custom-feedback">
                    Veuillez remplir ce champ
                </div>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" required>
                <div class="invalid-feedback custom-feedback">
                    Veuillez remplir ce champ
                </div>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                <div class="invalid-feedback custom-feedback">
                    Veuillez remplir ce champ
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="template.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .custom-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
            display: none;
        }
        .is-invalid ~ .custom-feedback {
            display: block;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            const inputs = form.querySelectorAll('input[required]');

            // Add input event listeners for real-time validation
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                    }
                });

                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('is-invalid');
                    }
                });
            });

            // Form submission validation
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Add invalid class to empty required fields
                    inputs.forEach(input => {
                        if (input.value.trim() === '') {
                            input.classList.add('is-invalid');
                        }
                    });
                }
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>