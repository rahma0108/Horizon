<?php
include '../controller/reservationC.php';

$reservationsC = new reservationsC();
$reservationsC->deleteReservation($_GET['id']);

header('Location: adminreservation.php');
exit;
?>
