<?php
require '../vendor/autoload.php'; // if you used Composer

use Dompdf\Dompdf;
use Dompdf\Options;

include '../controller/reservationC.php';
include '../controller/eventsC.php';

$reservationsC = new reservationsC();
$eventsC = new eventsC();

// Get reservation ID
$reservation_id = $_GET['reservation_id'] ?? null;

if ($reservation_id) {
    $reservation = $reservationsC->getReservationById($reservation_id);

    if ($reservation && $reservation['status'] === 'confirmed') {
        $event = $eventsC->getEventById($reservation['event_id']);

        if ($event) {
            // Generate ticket HTML
            $imgSrc = "http://localhost/webprojectevenemntilyes/vue/" . htmlspecialchars($event['image']);

$ticketHtml = "
    <div style='width:600px; padding:20px; border:2px solid #4CAF50; border-radius:10px; font-family:sans-serif;'>
        <h1 style='text-align:center;'> Your Event Ticket</h1>
        <img src='" . $imgSrc . "' style='width:100%; height:300px; object-fit:cover; border-radius:10px; margin-bottom:20px;'>
        <h2>" . htmlspecialchars($event['title']) . "</h2>
        <p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>
        <p><strong>Date:</strong> " . htmlspecialchars($event['event_date']) . "</p>
        <p><strong>Status:</strong> Confirmed </p>
    </div>
";

            // Create PDF
            $options = new Options();
            $options->set('isRemoteEnabled', true); // allow images
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($ticketHtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Output as download
            $dompdf->stream('ticket_' . $reservation_id . '.pdf', [
                'Attachment' => true
            ]);
            exit;
        }
    }
}

echo "Ticket cannot be generated.";
?>
