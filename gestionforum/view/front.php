<?php
$siteTitle = "Fitsense - Club Multisports";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($siteTitle); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #fff;
            min-height: 100vh;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .navbar:hover {
            background: rgba(0, 0, 0, 0.85);
        }

        .navbar .logo {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 2px;
        }

        .navbar .menu a {
            color: #fff;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .navbar .menu a:hover {
            color: #c5ff38;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            z-index: 1;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero .btn-group {
            display: flex;
            gap: 25px;
            justify-content: center;
        }

        .hero a {
            background: #c5ff38;
            color: #000;
            padding: 14px 30px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.3s;
        }

        .hero a:hover {
            background: #a8d82e;
            transform: translateY(-5px);
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .footer a {
            color: #c5ff38;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero .btn-group a {
                padding: 12px 25px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">FIT SENSE</div>
        <div class="menu">
            <a href="#">Accueil</a>
            <a href="#">Activités</a>
            <a href="#">Événements</a>
            <a href="#">Blog</a>
            <a href="#">Contact</a>
        </div>
    </nav>
    
    

    <footer class="footer">
        <p>&copy; 2025 Fitsense - Tous droits réservés. <a href="#">Mentions légales</a></p>
    </footer>
</body>
</html>