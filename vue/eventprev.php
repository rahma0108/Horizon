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
    <title>Document</title>
    <style>
          body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(151, 198, 34, 0.85), rgba(161, 203, 36, 0.85)), url('phtoback.jpg') no-repeat center center / cover;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 3rem auto;
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-left: 260px; 
            padding: 0 1rem;
            margin: 2rem 2rem 2rem 320px;
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
    margin-top: 1rem; /* ✅ Add this line */
}

        .info {
            font-size: 1rem;
            line-height: 1.6;
            max-width: 800px;
    width: 100%;
    word-wrap: break-word; /* ✅ Wraps very long words/URLs */
    white-space: pre-wrap; /* ✅ Respects newlines and wraps */
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
    top: 0;
    left: 0;
    overflow-y: auto; /* allow scroll inside sidebar if content exceeds */
    z-index: 1000; /* stays above the page */
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

        .nav-content i {
            width: 28px;
            height: 28px;
        }

        .container {
            max-width: 1000px;
            margin: 2rem 2rem 2rem 270px;
            padding: 0 1rem;
            
            
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
            margin: 0;
            font-weight: 600;
            color:black;
            
        }

        h2 {
            font-size: 1.5rem;
            margin: 0 0 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #1e3a8a;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.925rem;
            color: #1f2937;
            font-weight: 500;
        }

        input, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.925rem;
            transition: all 0.2s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }
        img {
    max-width: 100%;
}

        button {
            background: linear-gradient(135deg,rgb(202, 235, 37) 0%,rgb(122, 209, 14) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            width: 100%;
            justify-content: center;
        }

        button:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        button:active {
            transform: translateY(0);
        }

        button i {
            width: 20px;
            height: 20px;
        }

        .required {
            color: #ef4444;
            margin-left: 0.25rem;
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

        .sport-icons {
            position: fixed;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.05;
            z-index: -1;
        }
        .gall_bot {
    padding: 28px 21px 30px;
}

        #preview {
            max-height: 300px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            display: none;
            margin-top: 1rem;
        }
        .gall_bot  .text1 {
    margin-bottom: 22px;
}
.text1 {
    font-size: 30px;
    margin-bottom: 2px;
    line-height: auto;
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

    
    <div class="sport-icons"></div>
    
        
    <div class="container">
        <?php if ($p): ?>
            <img src="<?= htmlspecialchars($p['image']) ?>" class="event-image" alt="Event Image">
            <h1><?= htmlspecialchars($p['title']) ?></h1>
            <div class="info">
                <p><span class="label">Sport Type:</span> <?= htmlspecialchars($p['sport_type']) ?></p>
                <p><span class="label">Location:</span> <?= htmlspecialchars($p['location']) ?></p>
                <p><span class="label">Day:</span> <?= date('l, F j, Y', strtotime($p['event_date'])) ?></p>
<p><span class="label">Time:</span> <?= date('H:i', strtotime($p['event_date'])) ?></p>
                <p><span class="label">Nembre de place restant:</span> <?= htmlspecialchars($p['max_participants']) ?></p>
                <p><span class="label">Created By:</span> <?= htmlspecialchars($p['createdby']) ?></p>
                <p><span class="label">Description:</span> <?= nl2br(htmlspecialchars($p['description'])) ?></p>
                
            </div>
            <a href="homeevents.php" class="back-link">← Back to Events</a>
        <?php else: ?>
            <p>Event not found.</p>
        <?php endif; ?>
    </div>
    
</body>
</html>