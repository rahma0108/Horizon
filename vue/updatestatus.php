<?php
include '../controller/reservationC.php';
include '../model/reservation.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status']; // 'confirmed' or 'cancelled'

    $reservationsC = new reservationsC();

    // Get the existing reservation
    $reservationData = $reservationsC->getReservationById($id);

    if ($reservationData) {
        $reservation = new Reservation(
            $reservationData['event_id'],
            $reservationData['user_id'],
            $status
        );
        $reservation->setReservationDate($reservationData['reservation_date']); // keep original date

        $reservationsC->updateReservation($reservation, $id);
    }

    header('Location: adminreservation.php');
    exit;
} else {
    echo "Missing ID or status.";
}
