<?php
include '../controller/eventsC.php';
include '../model/events.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $pc = new eventsC();
    $p = $pc->getEventById($id); // Fetch event details based on the ID
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <style>
        .reserve-btn {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
            padding: 0.55rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
            line-height: 1;
            transition: background 0.2s, transform 0.2s;
            display: inline-block;
        }
        .reserve-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(151, 198, 34, 0.85), rgba(161, 203, 36, 0.85)), url('phtoback.jpg') no-repeat center center / cover;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 2rem 2rem 2rem 320px;
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .event-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            margin-top: 1rem;
        }
        .info {
            font-size: 1rem;
            line-height: 1.6;
            max-width: 800px;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            text-decoration: none;
            color: #1d4ed8;
            font-weight: bold;
        }
        .success-message {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #059669;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .success-message.show {
            opacity: 1;
        }
        .sidebar {
            width: 250px;
            background: #222;
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
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

    <!-- ✅ Success Message if redirected from reservation -->
    <?php if (isset($_GET['reserved']) && $_GET['reserved'] == 1): ?>
        <div class="success-message show" id="successMsg">
            🎉 Reservation completed successfully!
                 your reservation in status "pending",just wait the conformation
                                 welcome
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('successMsg');
                if (msg) msg.classList.remove('show');
            }, 3000);
        </script>
    <?php endif; ?>

    <div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="success-message show" id="alreadyReserved" style="background:#ef4444;">
            ❗ You already reserved this event.
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('alreadyReserved');
                if (msg) msg.classList.remove('show');
            }, 3000);
        </script>
    <?php endif; ?>
        <?php if ($p): ?>
            <img src="<?= htmlspecialchars($p['image']) ?>" class="event-image" alt="Event Image">
            <h1><?= htmlspecialchars($p['title']) ?></h1>
            <div class="info">
                <p><span class="label">Sport Type:</span> <?= htmlspecialchars($p['sport_type']) ?></p>
                <p><span class="label">Location:</span> <?= htmlspecialchars($p['location']) ?></p>
                <p><span class="label">Day:</span> <?= date('l, F j, Y', strtotime($p['event_date'])) ?></p>
                <p><span class="label">Time:</span> <?= date('H:i', strtotime($p['event_date'])) ?></p>
                <p><span class="label">Remaining Spots:</span> <?= htmlspecialchars($p['max_participants']) ?></p>
                <p><span class="label">Created By:</span> <?= htmlspecialchars($p['createdby']) ?></p>
                <p><span class="label">Description:</span> <?= nl2br(htmlspecialchars($p['description'])) ?></p>
            </div>
            <a href="homeevents.php" class="back-link">← Back to Events</a>
            <a href="reserve_event.php?id=<?= htmlspecialchars($p['id']) ?>" class="reserve-btn">Reserve Now</a>
        <?php else: ?>
            <p>Event not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
