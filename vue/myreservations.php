<?php
include '../controller/reservationC.php';
include '../controller/eventsC.php';


$reservationsC = new reservationsC();
$eventsC = new eventsC();

$user_id = $_GET['user_id'] ?? 0;

// Get all reservations
$reservations = $reservationsC->listReservations();

// Filter only this user's reservations
$userReservations = array_filter($reservations, fn($r) => $r['user_id'] == $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Reservations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(151, 198, 34, 0.85), rgba(161, 203, 36, 0.85)),
                        url('phtoback.jpg') no-repeat center center / cover;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #222;
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }
        nav {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-content {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            margin-left: calc(250px + 2rem);
        }
        .gall_block {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
            min-height: 320px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .gall_block:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        .maxheight {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .gall_bot {
            padding: 1.5rem;
        }
        .gall_block img {
            border-radius: 1rem 1rem 0 0;
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gall_block:hover img {
            transform: scale(1.03);
        }
        .grid_4 {
            flex: 1 1 calc(25% - 1.5rem);
            box-sizing: border-box;
            margin-bottom: 2rem;
            min-width: 250px;
            max-width: calc(25% - 1.5rem);
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.05rem;
        }
        .text1 {
            font-size: 30px;
            margin-bottom: 2px;
        }
        .text1 a {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
            color: #111;
            text-decoration: none;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }
        .text2 {
            font-size: 12px;
            color: gray;
            margin-bottom: 6px;
            line-height: 14px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>HORIZON</h2>
    <a href="#">Users</a>
    <a href="#">Reservation</a>
    <a href="admincontrole.php">Events</a>
    <a href="#">Reports</a>
    <a href="#">Reclamations</a>
    <a href="#">Shop Details</a>
    <a href="#">Settings</a>
    <a href="#">Logout</a>
</div>

<nav>
    <div class="nav-content">
        <h1>My Reservations</h1>
    </div>
</nav>

<section class="content gallery pad1">
    <div class="container">
        <div class="row">
            <?php foreach ($userReservations as $res):
                $event = $eventsC->getEventById($res['event_id']);
                if (!$event) continue;
            ?>
                <div class="grid_4">
                    <div class="gall_block">
                        <div class="maxheight">
                            <a class="gall_item">
                                <img src="<?= htmlspecialchars($event['image']) ?>" alt="">
                            </a>
                            <div class="gall_bot">
                                <div class="text1"><a><?= htmlspecialchars($event['title']) ?></a></div>
                                <div class="text2">
                                    <?= htmlspecialchars($event['location']) ?> — <?= htmlspecialchars($event['event_date']) ?>
                                </div>
                                <div>Status: <strong><?= htmlspecialchars($res['status']) ?></strong></div>
                                <br>
                                <div style="display: flex; gap: 10px;">
    <a href="cancelreservation.php?id=<?= $res['id'] ?>&user_id=<?= $user_id ?>"
       onclick="return confirm('Are you sure you want to cancel this reservation?')"
       style="background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none;">Cancel Reservation</a>

    <?php if ($res['status'] == 'confirmed'): ?>
        <a href="generate_ticket.php?reservation_id=<?= $res['id'] ?>" 
           style="background-color: #4CAF50; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none;">Get My Ticket</a>
    <?php endif; ?>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
</body>
</html>
