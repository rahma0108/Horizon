<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include '../controller/reservationC.php';
include '../controller/eventsC.php';

$reservationsC = new reservationsC();
$eventsC = new eventsC();

$reservation_id = $_GET['reservation_id'] ?? null;

if ($reservation_id) {
    $reservation = $reservationsC->getReservationById($reservation_id);

    if ($reservation && $reservation['status'] === 'confirmed') {
        $event = $eventsC->getEventById($reservation['event_id']);

        if ($event) {
            $imgSrc = "http://localhost/webprojectevenemntilyes/vue/" . htmlspecialchars($event['image']);
            $qrData = urlencode("Reservation: " . $reservation_id . " | Event: " . $event['title']);
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=$qrData&size=100x100";

            $ticketHtml = "
                <html>
                <head>
                    <style>
                        * {
                            box-sizing: border-box;
                        }
                        body {
                            font-family: 'Segoe UI', sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
                        }
                        .ticket {
                            width: 700px;
                            height: 250px;
                            display: flex;
                            border-radius: 12px;
                            overflow: hidden;
                            background-color: #1e1e80;
                            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                            color: #000;
                        }
                        .ticket-left {
                            width: 60%;
                            padding: 20px;
                            background-color: rgb(24, 186, 21);
                        }
                        .ticket-left h2 {
                            margin: 0 0 10px;
                            font-size: 24px;
                            color: #1e1e80;
                        }
                        .ticket-left p {
                            margin: 5px 0;
                            font-size: 14px;
                            color: #222;
                        }
                        .ticket-right {
                            width: 40%;
                            position: relative;
                            background-color: #000;
                        }
                        .ticket-right img.main {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            opacity: 0.8;
                        }
                        .qr-code {
                            position: absolute;
                            bottom: 12px;
                            right: 12px;
                            background: #fff;
                            padding: 5px;
                            border-radius: 6px;
                        }
                        .qr-code img {
                            width: 70px;
                        }
                    </style>
                </head>
                <body>
                    <div class='ticket'>
                        <div class='ticket-left'>
                            <h2>" . htmlspecialchars($event['title']) . "</h2>
                            <p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>
                            <p><strong>Date:</strong> " . htmlspecialchars($event['event_date']) . "</p>
                            <p><strong>Status:</strong> Confirmed</p>
                            <p><strong>Ticket ID:</strong> #" . $reservation_id . "</p>
                            <p style='margin-top: 20px; font-style: italic; color: #555;'>Please show this ticket at the entrance.</p>
                        </div>
                        <div class='ticket-right'>
                            <img class='main' src='" . $imgSrc . "' alt='Event Image'>
                            <div class='qr-code'>
                                <img src='" . $qrCodeUrl . "' alt='QR Code'>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $options = new Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($ticketHtml);
            $dompdf->setPaper([0, 0, 700, 250], 'portrait'); // 1-page ticket
            $dompdf->render();

            $dompdf->stream('ticket_' . $reservation_id . '.pdf', [
                'Attachment' => true
            ]);
            exit;
        }
    }
}

echo "Ticket cannot be generated.";
?>
