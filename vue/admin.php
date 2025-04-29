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
            max-width: 800px;
            margin: 2rem auto;
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

        #preview {
            max-height: 300px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            display: none;
            margin-top: 1rem;
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
            <h1> Add Sports Events </h1>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h2><i data-lucide="plus-circle"></i> Add New Event</h2>
            <form id="eventForm" action="ajout.php" method="POST">
    <div class="form-group">
        <label for="title">Event Title<span class="required">*</span></label>
        <input type="text" id="title" name="title">
    </div>
    <div class="form-group">
        <label for="description">Description<span class="required">*</span></label>
        <textarea id="description" name="descreption"></textarea>
    </div>
    <div class="form-group">
        <label for="sport_type">Sport Type<span class="required">*</span></label>
        <input type="text" id="sport_type" name="sporttype">
    </div>
    <div class="form-group">
        <label for="location">Location<span class="required">*</span></label>
        <input type="text" id="location" name="location">
    </div>
    <div class="form-group">
        <label for="event_date">Event Date<span class="required">*</span></label>
        <input type="datetime-local" name="event_date" id="event_date">
    </div>
    <div class="form-group">
        <label for="max_participants">Maximum Participants<span class="required">*</span></label>
        <input type="number" name="max_participants" id="max_participants" min="1">
    </div>
    <div class="form-group">
        <label for="created_by">Created By<span class="required">*</span></label>
        <input type="text" name="created_by" id="created_by">
    </div>
    <div class="form-group">
        <label for="image">Image<span class="required">*</span></label>
        <input type="text" name="image" id="image">
    </div>

    <button type="submit"><i data-lucide="plus"></i> Add Event</button>
</form>

<script>
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        var fields = ['title', 'description', 'sport_type', 'location', 'event_date', 'max_participants', 'created_by', 'image'];
        var allFilled = true;
        var firstEmpty = '';

        fields.forEach(function(id) {
            var input = document.getElementById(id);
            if (!input.value.trim()) {
                allFilled = false;
                if (!firstEmpty) firstEmpty = id;
            }
        });

        if (!allFilled) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            document.getElementById(firstEmpty).focus();
        }
        var title = document.getElementById('title').value.trim();
        if (title.length <= 1) {
            e.preventDefault();
            alert('The title must be longer than 1 character.');
            document.getElementById('title').focus();
            return;
        }
        var maxParticipants = parseInt(document.getElementById('max_participants').value);
        if (isNaN(maxParticipants) || maxParticipants <= 0) {
            e.preventDefault();
            alert('Maximum participants must be a number greater than 0.');
            document.getElementById('max_participants').focus();
            return;
        }
        var eventDate = new Date(document.getElementById('event_date').value);
        var now = new Date();
        if (eventDate <= now) {
            e.preventDefault();
            alert('Event date must be in the future.');
            document.getElementById('event_date').focus();
            return;
        }
    });
</script>


</body>
</html>
