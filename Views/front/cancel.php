<?php
$reservationId = $_GET['reservation_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement Annulé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-warning text-center" role="alert">
            <h1>Paiement Annulé</h1>
            <p>Le paiement pour la réservation #<?= htmlspecialchars($reservationId) ?> n'a pas été effectué.</p>
            <a href="templateReservation.php" class="btn btn-primary mt-3">Retour aux Réservations</a>
        </div>
    </div>
</body>
</html>
