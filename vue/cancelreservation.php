<?php
include '../controller/reservationC.php';

if (isset($_GET['id'])) {
    $id = $_GET['id']; // reservation ID

    $reservationsC = new reservationsC();
    $reservationsC->deleteReservation($id);

    // Optional: redirect back to the reservation list
    header('Location: myreservations.php?user_id=' . $_GET['user_id']);
    exit;
} else {
    echo "Reservation ID missing.";
}
