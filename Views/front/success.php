<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';

// Stripe configuration
$stripeConfig = require_once __DIR__ . '/../../stripe_config.php';
require_once __DIR__ . '/../../vendor/stripe/init.php';

\Stripe\Stripe::setApiKey($stripeConfig['stripe_secret_key']);
\Stripe\Stripe::setVerifySslCerts(false);

// Get session and reservation IDs
$sessionId = $_GET['session_id'] ?? null;
$reservationId = $_GET['reservation_id'] ?? null;

if (!$sessionId || !$reservationId) {
    die('Missing required parameters');
}

try {
    // Retrieve the Checkout Session
    $session = \Stripe\Checkout\Session::retrieve($sessionId);
    
    // Get the Payment Intent ID
    $paymentIntentId = $session->payment_intent;

    // Verify payment status
    $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
    
    if ($paymentIntent->status !== 'succeeded') {
        throw new Exception('Payment not completed successfully');
    }

    // Update reservation status and save Payment Intent ID
    $reservationController = new ReservationController();
    $paymentSuccess = $reservationController->updateReservationPayment($reservationId, $paymentIntentId);

    if (!$paymentSuccess) {
        throw new Exception('Failed to update reservation payment status');
    }

    // Render success page
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement Réussi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <h1>Paiement Réussi !</h1>
            <p>Votre réservation #<?= htmlspecialchars($reservationId) ?> est confirmée.</p>
            <a href="templateReservation.php" class="btn btn-primary">Retour aux Réservations</a>
        </div>
    </div>
</body>
</html>
<?php
} catch (Exception $e) {
    error_log('Payment Verification Error: ' . $e->getMessage());
    
    // Render error page
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Erreur de Paiement</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-danger text-center">
                <h1>Erreur de Paiement</h1>
                <p>Une erreur est survenue lors du traitement de votre paiement.</p>
                <p>Détails : <?= htmlspecialchars($e->getMessage()) ?></p>
                <a href="templateReservation.php" class="btn btn-primary">Retour aux Réservations</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}