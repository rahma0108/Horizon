
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
    <title>Document</title>
</head>



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

        .nav-content i {
            width: 28px;
            height: 28px;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            margin-left: calc(250px + 2rem);
            
            
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
    color:gray;  
    margin-bottom: 2px;
   
    line-height: 10px;
}
.gall_block {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 2rem;
    min-height: 320px; /* adjust as needed */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.maxheight {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.gall_block:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
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
    flex: 1 1 calc(25% - 1.5rem); /* 4 per row with spacing */
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
    <nav>
        <div class="nav-content">
            <i data-lucide="trophy"></i>
            <h1> All events </h1>
        </div>
    </nav>



    <section class="content gallery pad1">
  <div class="container">
    <div class="row">
    
    <?php foreach ($events as $event): ?>

                    
                    <div class="grid_4">
                        <div class="gall_block">
                        <div class="maxheight">
                            <a class="gall_item"><img src="<?= htmlspecialchars($event['image']) ?>" alt=""></a>
                            <div class="gall_bot">
                            <div class="text1"><a><?= htmlspecialchars($event['title']) ?> </a></div>
                            <div class="text2"><a><?= htmlspecialchars($event['location']) ?> - <?= htmlspecialchars($event['event_date']) ?> </a></div>
                            

                            <?= htmlspecialchars(substr($event['description'], 0, 30)) ?>....
                            <br>
                            <a href="eventprev.php?id=<?= $event['id'] ?>" >See More</a></div>
                        </div>
                        </div>
                    </div>




                    <?php endforeach; ?>




    </div>
    </div>
    </section>
    </body>
    </HTML>