<?php
include '../controller/reservationC.php';

$reservationsC = new reservationsC();
$reservations = $reservationsC->listReservations();

// Optional: handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $reservationsC->deleteReservation($id);
    header('Location: reservationcontrole.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations List</title>
    <style>
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(151, 198, 34, 0.85), rgba(161, 203, 36, 0.85)),
                        url('phtoback.jpg') no-repeat center center / cover;
            min-height: 100vh;
        }

        nav {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-content {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .container {
            max-width: 1350px;
            margin: 2rem auto;
            padding: 1rem;
            margin-left: 250px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            font-size: 1.75rem;
            color: black;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 0.95rem;
        }

        th {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .delete-btn {
            padding: 6px 12px;
            font-size: 0.85rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            background-color: #ef4444;
            color: white;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>HORIZON</h2>
    <a href="#">Users</a>
    <a href="reservationcontrole.php">Reservation</a>
    <a href="admincontrole.php">Events</a>
    <a href="#">Reports</a>
    <a href="#">Reclamations</a>
    <a href="#">Shop Details</a>
    <a href="#">Settings</a>
    <a href="#">Logout</a>
</div>

<nav>
    <div class="nav-content">
        <h1>Reservation Admin Panel</h1>
    </div>
</nav>

<div class="container">
    <div class="card">
        <h1>Reservations List</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event ID</th>
                    <th>User ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['id']) ?></td>
                    <td><?= htmlspecialchars($res['event_id']) ?></td>
                    <td><?= htmlspecialchars($res['user_id']) ?></td>
                    <td><?= htmlspecialchars($res['reservation_date']) ?></td>
                    <td><?= htmlspecialchars($res['status']) ?></td>
                    <td class="actions">
                    <td class="actions">
    <a href="updatestatus.php?id=<?= $res['id'] ?>&status=confirmed"
        class="delete-btn"
        style="background-color: #10b981;">Confirm</a>

    <a href="updatestatus.php?id=<?= $res['id'] ?>&status=cancelled"
        class="delete-btn"
        style="background-color: #f59e0b;">Cancel</a>

    <a href="deletreservation.php?id=<?= $res['id'] ?>"
        class="delete-btn"
        onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
</td>


                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
