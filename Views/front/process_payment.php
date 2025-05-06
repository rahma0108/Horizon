<?php
require_once __DIR__ . '/../../Controller/ReservationController.php';

header('Content-Type: application/json');

// Stripe test secret key (replace with your actual secret key)
//$stripeSecretKey = 'sk_test_51PGnmBLwKQmqD8Ux5Ld9qLHEcIFqMtqaWnqCvtGGzFqBnQXZqYXGkHvLYjQoYZZAJqKkNxDzjQZFGQqsGVQHvMJT00qVMbVnEy';

// Get the raw POST data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

try {
    // Validate input
    if (!isset($data['token'], $data['reservationId'], $data['amount'])) {
        throw new Exception('Missing payment details');
    }

    // Initialize Stripe
  //  \Stripe\Stripe::setApiKey($stripeSecretKey);

    // Create a charge on Stripe's servers
    $charge = \Stripe\Charge::create([
        'amount' => $data['amount'], // Amount in cents
        'currency' => 'eur',
        'source' => $data['token'],
        'description' => 'Reservation Payment #' . $data['reservationId']
    ]);

    // Update reservation status in database
    $reservationController = new ReservationController();
    $updateResult = $reservationController->updateReservationStatus($data['reservationId'], 'Payé');

    echo json_encode([
        'success' => true,
        'message' => 'Payment processed successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
