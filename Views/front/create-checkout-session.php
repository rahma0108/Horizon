<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';

// Load Stripe configuration
$stripeConfig = require_once __DIR__ . '/../../stripe_config.php';

// Set Stripe API key from config
\Stripe\Stripe::setApiKey($stripeConfig['stripe_secret_key']);';

// Include Stripe library manually
require_once __DIR__ . '/../../vendor/stripe/init.php';

// Set Stripe API key
\Stripe\Stripe::setApiKey($stripeSecretKey);

// Disable SSL verification (DEVELOPMENT ONLY)
\Stripe\Stripe::setVerifySslCerts(false);

// Get reservation and course details
$reservationId = $_POST['reservation_id'] ?? null;
$coursPrix = $_POST['cours_prix'] ?? 0;

if (!$reservationId) {
    die('Reservation ID is required');
}

// Create Stripe Checkout Session
try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Paiement de Réservation #' . $reservationId,
                ],
                'unit_amount' => $coursPrix * 100, // Convert to cents
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/Projetweb1/Views/front/success.php?reservation_id=' . $reservationId,
        'cancel_url' => 'http://localhost/Projetweb1/Views/front/cancel.php?reservation_id=' . $reservationId,
    ]);

    // Redirect to Stripe Checkout
    header("Location: " . $session->url);
    exit();
} catch (Exception $e) {
    // Handle any errors
    echo 'Erreur: ' . $e->getMessage();
    exit();
}
