<?php
include '../controller/eventsC.php'; // Make sure path is correct
$eventsC = new eventsC();
$stats = $eventsC->getEventStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Events Admin</title>
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
            margin-left: 250px;
        }
        .nav-content {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
            margin-left: 270px;
        }
        h1 {
            font-size: 1.75rem;
            margin: 0;
            font-weight: 600;
            color: black;
        }
        .flex-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 2rem;
            margin-top: 2rem;
        }
        .table-container, .chart-container {
            flex: 1;
            min-width: 300px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }
        th {
            background: #a7f3d0;
            color: #065f46;
            font-size: 1.1rem;
        }
        tr:hover {
            background-color: #f0fdf4;
        }
        #myChart {
            max-width: 100%;
            height: auto;
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

<div class="sport-icons"></div>

<nav>
    <div class="nav-content">
        <i data-lucide="trophy"></i>
        <h1>🏆 Best Events Rated by Users</h1>
    </div>
</nav>

<div class="container">
    <div class="flex-container">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Number of Reservations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats as $index => $row): ?>
                        <tr <?php if ($index == 0) echo 'style="background: #d4edda;"'; ?>>
                            <td>
                                <?php echo htmlspecialchars($row['title']); ?>
                                <?php if ($index == 0): ?> 🥇<?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['reservation_count']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = <?php echo json_encode(array_column($stats, 'title')); ?>;
const data = <?php echo json_encode(array_column($stats, 'reservation_count')); ?>;

const ctx = document.getElementById('myChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Reservations',
            data: data,
            backgroundColor: [
                '#f87171',
                '#60a5fa',
                '#34d399',
                '#fbbf24',
                '#a78bfa',
                '#f472b6',
                '#38bdf8',
                '#4ade80'
            ],
            borderColor: 'white',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Reservations per Event'
            }
        }
    }
});
</script>

</body>
</html>
