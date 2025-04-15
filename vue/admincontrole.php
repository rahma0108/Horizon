<?php
include '../controller/eventsC.php';

$eventsC = new eventsC();
$events = $eventsC->listEvents();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $eventsC->deleteEvent($id);
    header('Location: events_list.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events List</title>
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
            margin-left:250px;
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

        .edit-btn, .delete-btn {
            padding: 6px 12px;
            font-size: 0.85rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .edit-btn {
            background-color: #3b82f6;
            color: white;
        }

        .edit-btn:hover {
            background-color: #2563eb;
        }

        .delete-btn {
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
        <h2>   HORIZON</h2>
        <a href="#"></a>
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
            <h1>Events Admin Panel</h1>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1>Events List</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Sport</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Max</th>
                        <th>Creator</th>
                        <th>Created</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['id']) ?></td>
                        <td><?= htmlspecialchars($event['title']) ?></td>
                        <td><?= htmlspecialchars(substr($event['description'], 0, 10)) ?>.....</td>
                        <td><?= htmlspecialchars($event['sport_type']) ?></td>
                        <td><?= htmlspecialchars($event['location']) ?></td>
                        <td><?= htmlspecialchars($event['event_date']) ?></td>
                        <td><?= htmlspecialchars($event['max_participants']) ?></td>
                        <td><?= htmlspecialchars($event['createdby']) ?></td>
                        <td><?= htmlspecialchars($event['created_at']) ?></td>
                        <td><?= htmlspecialchars($event['image']) ?></td>

                        <td class="actions">
                            <a href="pagemodif.php?id=<?= $event['id'] ?>" class="edit-btn">Edit</a>
                            <a href="delete.php?id=<?= $event['id'] ?>" 
                               class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
