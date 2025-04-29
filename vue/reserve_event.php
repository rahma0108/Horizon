<?php
include '../controller/reservationC.php';
include '../model/reservation.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $user_id = 999; // TODO: Replace with real session user_id

    $reservationsC = new reservationsC();

    // 🔍 Check if reservation already exists
    $existingReservations = $reservationsC->getReservationsByEvent($event_id);
    $alreadyReserved = false;

    foreach ($existingReservations as $res) {
        if ($res['user_id'] == $user_id) {
            $alreadyReserved = true;
            break;
        }
    }

    if ($alreadyReserved) {
        // 🚫 Already reserved – redirect with error message
        header("Location: eventprev.php?id=$event_id&error=1");
        exit;
    }

    // ✅ Not reserved yet – proceed
    $reservation = new Reservation($event_id, $user_id);
    $reservationsC->addReservation($reservation);

    // Redirect to event page with success message
    header("Location: eventprev.php?id=$event_id&reserved=1");
    exit;
} else {
    echo "No event selected.";
}
